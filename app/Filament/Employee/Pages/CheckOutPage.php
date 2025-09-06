<?php

namespace App\Filament\Employee\Pages;

use App\Models\Employee;
use App\Models\Location;
use App\Models\Checkin;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Employee\Resources\Checkins\CheckinResource;

class CheckOutPage extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $guard = 'employee';
    protected static ?string $slug = 'check-out';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-right-on-rectangle';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Check-Out Yap';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    
    public static function canAccess(): bool
    {
        $employee = Auth::guard('employee')->user();
        if (!$employee) return false;
        
        // Aktif check-in var mı kontrol et
        return Checkin::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->whereNull('checkout_time')
            ->exists();
    }

    protected string $view = 'filament.employee.pages.check-out';

    public ?array $data = [];
    public ?Checkin $activeCheckin = null;

    public function mount()
    {
        $employee = Auth::guard('employee')->user();
        
        // Aktif check-in var mı kontrol et
        $this->activeCheckin = Checkin::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->whereNull('checkout_time')
            ->first();
            
        if (!$this->activeCheckin) {
            Notification::make()
                ->title('Aktif Check-in Bulunamadı')
                ->body('Check-out yapabilmek için önce check-in yapmış olmanız gerekiyor.')
                ->warning()
                ->send();
                
            return redirect()->to(CheckinResource::getUrl('index'));
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('📍 Mevcut Check-in Bilgileri')
                    ->description('Aktif mesai bilgileriniz')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        ViewField::make('checkin_info')
                            ->view('forms.components.active-checkin-info', [
                                'checkin' => $this->activeCheckin
                            ])
                            ->dehydrated(false),
                    ]),

                Section::make('🗺️ Check-out Konumu')
                    ->description('Check-out yapmak için mevcut konumunuzu doğrulayın')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        ViewField::make('location_picker')
                            ->view('forms.components.employee-location-picker')
                            ->dehydrated(false),

                        Hidden::make('checkout_latitude')
                            ->required(),

                        Hidden::make('checkout_longitude')
                            ->required(),
                    ]),

                Section::make('📝 Mesai Notları')
                    ->description('Günlük mesainizle ilgili notlar ekleyebilirsiniz (isteğe bağlı)')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notlarınız')
                            ->placeholder('Bugünkü mesainizle ilgili notlarınızı buraya yazabilirsiniz...')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function checkOut()
    {
        try {
            $data = $this->form->getState();
            $employee = Auth::guard('employee')->user();

            if (!$this->activeCheckin) {
                Notification::make()
                    ->title('Hata!')
                    ->body('Aktif check-in bulunamadı.')
                    ->danger()
                    ->send();
                return;
            }

            $location = $this->activeCheckin->location;

            // Konum doğrulaması
            $validation = $location->validateCheckin(
                $employee, 
                $data['checkout_latitude'], 
                $data['checkout_longitude']
            );

            if (!$validation['valid']) {
                Notification::make()
                    ->title('Check-out Başarısız!')
                    ->body($validation['message'])
                    ->danger()
                    ->send();
                return;
            }

            // Check-out işlemini tamamla
            $this->activeCheckin->update([
                'checkout_time' => now(),
                'checkout_latitude' => $data['checkout_latitude'],
                'checkout_longitude' => $data['checkout_longitude'],
                'notes' => $data['notes'],
                'status' => 'completed',
            ]);

            Notification::make()
                ->title('Check-out Başarılı! ✅')
                ->body("Mesai tamamlandı! İyi günler dileriz.")
                ->success()
                ->duration(5000)
                ->send();

            // Check-in geçmişi sayfasına yönlendir
            return redirect()->to(CheckinResource::getUrl('index'));
            
        } catch (\Exception $e) {
            \Log::error('CheckOut error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Notification::make()
                ->title('Hata!')
                ->body('Check-out sırasında bir hata oluştu: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return 'Mesai Check-Out';
    }

    public function getHeading(): string
    {
        $employee = Auth::guard('employee')->user();
        return "Merhaba " . ($employee?->name ?? 'Çalışan') . "! 👋";
    }

    public function getSubheading(): string
    {
        return 'Mesainizi tamamlamak için check-out yapın';
    }
}
