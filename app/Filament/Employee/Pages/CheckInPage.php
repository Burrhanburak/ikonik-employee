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
use Filament\Schemas\Components\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\DateTimePicker;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Employee\Resources\Checkins\CheckinResource;

class CheckInPage extends Page implements HasForms
{
    use InteractsWithForms;
    
    
    protected static ?string $guard = 'employee';

    

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-camera';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Check-In Yap';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    protected string $view = 'filament.employee.pages.check-in';

    public ?array $data = [];
    
    public bool $checkInCompleted = false;
    public ?array $completedCheckIn = null;
    public bool $alreadyCheckedInToday = false;
    public ?Checkin $todayCheckin = null;

    public function mount(): void
    {
        $employee = Auth::guard('employee')->user();
        
        if ($employee) {
            // Bugün check-in yapıp yapmadığını kontrol et
            $this->todayCheckin = Checkin::where('employee_id', $employee->id)
                ->whereDate('created_at', today())
                ->where('status', '!=', 'completed')
                ->first();
                
            if ($this->todayCheckin) {
                $this->alreadyCheckedInToday = true;
            }
        }
        
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('⏰ Check-in Zamanı')
                    ->description('Check-in tarihi ve saati')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        DateTimePicker::make('checkin_time')
                            ->label('Check-in Zamanı')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('📍 Lokasyon Seçimi')
                    ->description('Check-in yapmak istediğiniz lokasyonu seçin')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Select::make('location_id')
                            ->label('Çalışma Lokasyonu')
                            ->options(function () {
                                $employee = Auth::guard('employee')->user();
                                if (!$employee) {
                                    \Log::info('No employee authenticated');
                                    return [];
                                }
                                
                                // Önce bugünkü çalışma lokasyonunu kontrol et
                                $todayLocation = $employee->getTodayLocation();
                                if ($todayLocation) {
                                    \Log::info('Today location found: ' . $todayLocation->name);
                                    return [$todayLocation->id => $todayLocation->name . ' (Bugünkü Çalışma Lokasyonunuz)'];
                                }
                                
                                // Bugünkü çalışma lokasyonu yoksa tüm yetkili lokasyonları göster
                                \Log::info('No today location, showing all authorized locations');
                                $locations = $employee->locations()
                                    ->pluck('locations.name', 'locations.id')
                                    ->toArray();
                                    
                                // Eğer hiç yetkili lokasyon yoksa tümünü göster
                                if (empty($locations)) {
                                    \Log::info('No authorized locations, showing all locations');
                                    return \App\Models\Location::pluck('name', 'id')->toArray();
                                }
                                
                                return $locations;
                            })
                            ->required()
                            ->searchable()
                            ->placeholder('Lokasyon seçiniz...')
                            ->helperText(function () {
                                $employee = Auth::guard('employee')->user();
                                $todayLocation = $employee ? $employee->getTodayLocation() : null;
                                
                                if ($todayLocation) {
                                    return "Bugün {$todayLocation->name} lokasyonunda çalışmanız planlanmış.";
                                }
                                
                                return 'Bugün için planlanmış çalışma lokasyonu bulunamadı. Yetkili olduğunuz lokasyonları görebilirsiniz.';
                            })
                    ]),

                Section::make('🗺️ Konum Doğrulama')
                    ->description('Mevcut konumunuzu doğrulayın')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        ViewField::make('location_picker')
                            ->view('forms.components.employee-location-picker')
                            ->dehydrated(false)
                            ->extraAttributes([
                                'class' => 'relative'
                            ]),

                        Hidden::make('latitude')
                            ->required(),

                        Hidden::make('longitude')
                            ->required(),
                    ]),

                Section::make('📸 Selfie Fotoğraf')
                    ->description('Webcam ile check-in fotoğrafınızı çekin')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        ViewField::make('webcam_capture')
                            ->view('forms.components.webcam-capture')
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Hidden::make('selfie_photo')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function checkIn()
    {
        try {
            \Log::info('CheckIn method started');
            
            $data = $this->form->getState();
            \Log::info('Form data:', $data);
            
            $employee = Auth::guard('employee')->user();
            \Log::info('Employee:', $employee ? $employee->toArray() : 'null');

        if (!$employee) {
            Notification::make()
                ->title('Hata!')
                ->body('Giriş yapmış kullanıcı bulunamadı.')
                ->danger()
                ->send();
            return;
        }

        $location = Location::find($data['location_id']);

        if (!$location) {
            Notification::make()
                ->title('Hata!')
                ->body('Seçilen lokasyon bulunamadı.')
                ->danger()
                ->send();
            return;
        }

        // Konum doğrulaması
        $validation = $location->validateCheckin($employee, $data['latitude'], $data['longitude']);

        if (!$validation['valid']) {
            Notification::make()
                ->title('Check-in Başarısız!')
                ->body($validation['message'])
                ->danger()
                ->send();
            return;
        }

        // Check-in kaydet
        $checkin = Checkin::create([
            'employee_id' => $employee->id,
            'location_id' => $location->id,
            'shift_id' => null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'selfie_photo' => $data['selfie_photo'],
            'created_at' => $data['checkin_time'],
        ]);

        // Başarı durumunu ayarla - artık gerek yok çünkü redirect yapacağız
        // $this->checkInCompleted = true;
        // $this->completedCheckIn = [
        //     'employee_name' => $employee->name,
        //     'location_name' => $location->name,
        //     'checkin_time' => $data['checkin_time'],
        //     'checkin_id' => $checkin->id
        // ];

        Notification::make()
            ->title('Check-in Başarılı! ✅')
            ->body("Merhaba {$employee->name}! {$location->name} lokasyonunda başarıyla check-in yaptınız.")
            ->success()
            ->duration(5000)
            ->send();
            
            // Check-in geçmişi sayfasına yönlendir
            return redirect()->to(CheckinResource::getUrl('index'));
            
        } catch (\Exception $e) {
            \Log::error('CheckIn error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Notification::make()
                ->title('Hata!')
                ->body('Check-in sırasında bir hata oluştu: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetCheckIn(): void
    {
        $this->checkInCompleted = false;
        $this->completedCheckIn = null;
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return 'Mesai Check-In';
    }

    public function getHeading(): string
    {
        $employee = Auth::guard('employee')->user();
        return "Merhaba " . ($employee?->name ?? 'Çalışan') . "! 👋";
    }

    public function getSubheading(): string
    {
        return 'Mesaiye başlamak için check-in yapın';
    }

}