<?php

namespace App\Filament\Employee\Pages;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Checkin;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PublicCheckInPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.employee.pages.public-check-in';
    
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'check-in';
    
    public static function canAccess(): bool
    {
        return true; // Always accessible, even without authentication
    }
    
    public ?array $data = [];
    public bool $isAuthenticated = false;
    public ?Employee $employee = null;

    public function mount(): void
    {
        $this->isAuthenticated = Auth::guard('employee')->check();
        $this->employee = Auth::guard('employee')->user();
        
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Authentication Section (only show if not logged in)
                Section::make('👤 Giriş Bilgileri')
                    ->description('Check-in yapmak için sistemde kayıtlı hesabınızla giriş yapın')
                    ->icon('heroicon-o-user')
                    ->visible(fn () => !$this->isAuthenticated)
                    ->schema([
                        TextInput::make('email')
                            ->label('E-posta Adresi')
                            ->email()
                            ->required()
                            ->placeholder('ornek@firma.com')
                            ->helperText('Sistemde kayıtlı e-posta adresinizi girin'),
                            
                        TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->required()
                            ->placeholder('Şifrenizi girin'),
                    ]),

                // Location Section  
                Section::make('📍 Lokasyon Seçimi')
                    ->description('Check-in yapmak istediğiniz lokasyonu seçin')
                    ->icon('heroicon-o-map-pin')
                    ->visible(fn () => $this->isAuthenticated)
                    ->schema([
                        Select::make('location_id')
                            ->label('Çalışma Lokasyonu')
                            ->options(function () {
                                if (!$this->employee) return [];
                                
                                return $this->employee->locations()
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->required()
                            ->searchable()
                            ->placeholder('Lokasyon seçiniz...')
                            ->helperText('Sadece yetki verilen lokasyonlara check-in yapabilirsiniz')
                            ->reactive(),

                        ViewField::make('location_info')
                            ->view('forms.components.location-info')
                            ->visible(fn ($get) => $get('location_id')),
                    ]),

                // Location Verification Section
                Section::make('🗺️ Konum Doğrulama')
                    ->description('Mevcut konumunuzu doğrulayın')
                    ->icon('heroicon-o-globe-alt')
                    ->visible(fn () => $this->isAuthenticated)
                    ->schema([
                        ViewField::make('location_picker')
                            ->view('forms.components.employee-location-picker')
                            ->dehydrated(false),

                        Hidden::make('latitude')
                            ->required(),

                        Hidden::make('longitude')
                            ->required(),
                    ]),

                // Photo Section
                Section::make('📸 Selfie Fotoğraf')
                    ->description('Check-in fotoğrafınızı çekin')
                    ->icon('heroicon-o-camera')
                    ->visible(fn () => $this->isAuthenticated)
                    ->schema([
                        ViewField::make('webcam_capture')
                            ->view('forms.components.webcam-capture')
                            ->dehydrated(false),

                        FileUpload::make('selfie_photo')
                            ->label('Check-in Fotoğrafı')
                            ->image()
                            ->required()
                            ->directory('checkins')
                            ->visibility('private')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imageEditor()
                            ->helperText('Kamera ile çekin veya galeriden seçin')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function authenticate(): void
    {
        $data = $this->form->getState();

        // Try to authenticate with existing employee
        if (Auth::guard('employee')->attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            $this->isAuthenticated = true;
            $this->employee = Auth::guard('employee')->user();
            
            Notification::make()
                ->title('Giriş Başarılı! 👋')
                ->body("Hoş geldin {$this->employee->name}!")
                ->success()
                ->send();
                
            $this->form->fill();
        } else {
            // Check if employee exists but wrong password
            $employee = Employee::where('email', $data['email'])->first();
            
            if ($employee) {
                Notification::make()
                    ->title('Şifre Hatalı!')
                    ->body('E-posta adresiniz doğru ancak şifreniz yanlış.')
                    ->danger()
                    ->send();
            } else {
                Notification::make()
                    ->title('Kullanıcı Bulunamadı!')
                    ->body('Bu e-posta adresi ile kayıtlı çalışan bulunamadı. Lütfen yöneticinizle iletişime geçin.')
                    ->danger()
                    ->send();
            }
            return;
        }
    }

    public function checkIn(): void
    {
        if (!$this->isAuthenticated) {
            $this->authenticate();
            return;
        }

        $data = $this->form->getState();

        $location = Location::find($data['location_id']);

        if (!$location) {
            Notification::make()
                ->title('Hata!')
                ->body('Seçilen lokasyon bulunamadı.')
                ->danger()
                ->send();
            return;
        }

        // Location validation
        $validation = $location->validateCheckin($this->employee, $data['latitude'], $data['longitude']);

        if (!$validation['valid']) {
            Notification::make()
                ->title('Check-in Başarısız!')
                ->body($validation['message'])
                ->danger()
                ->send();
            return;
        }

        // Save check-in
        $checkin = Checkin::create([
            'employee_id' => $this->employee->id,
            'location_id' => $location->id,
            'shift_id' => null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'selfie_photo' => $data['selfie_photo'],
        ]);

        Notification::make()
            ->title('Check-in Başarılı! ✅')
            ->body("Merhaba {$this->employee->name}! {$location->name} lokasyonunda başarıyla check-in yaptınız.")
            ->success()
            ->duration(5000)
            ->send();

        // Reset form
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return 'Mesai Check-In';
    }

    public function getHeading(): string
    {
        if ($this->isAuthenticated && $this->employee) {
            return "Merhaba {$this->employee->name}! 👋";
        }
        
        return 'Mesai Check-In Sistemi';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label($this->isAuthenticated ? 'Check-in Yap' : 'Giriş Yap / Kayıt Ol')
                ->color('success')
                ->size('lg')
                ->action('checkIn')
        ];
    }
}
