<?php

namespace App\Filament\Employee\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use App\Models\Employee;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Size;
use Filament\Facades\Filament;

class Register extends BaseRegister
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->callHook('beforeFill');

        $this->form->fill();

        $this->callHook('afterFill');
    }

    protected function getUserModel(): string
    {
        return Employee::class;
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Ad Soyad')
            ->autocomplete('name')
            ->dehydrated(fn ($state): bool => filled($state))
            ->placeholder('Ad Soyad')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('E-posta')
            ->email()
            ->autocomplete('email')
            ->dehydrated(fn ($state): bool => filled($state))
            ->placeholder('ornek@gmail.com')
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Şifre')
            ->password()
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state))
            ->minLength(8)
            ->helperText('Minimum 8 karakter olmalıdır')
            ->placeholder('Güçlü bir şifre seçiniz')
            ->columnSpanFull()
            ->revealable()
            ->required()
            ->maxLength(255);
    }
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('password_confirmation')
            ->label('Şifre Tekrarı')
            ->password()
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state))
            ->minLength(8)
            ->helperText('Minimum 8 karakter olmalıdır')
            ->placeholder('Güçlü bir şifre seçiniz')
            ->columnSpanFull()
            ->revealable()
            ->required()
            ->maxLength(255);
    }

    protected function getFormSchema(): array
    {
        return [
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            Action::make('employeeLogin')
                ->label('Giriş Yap')
                ->link()
                ->button()

                ->url(route('filament.employee.auth.login'))
         
        ];
    }


    public function getTitle(): string | Htmlable
    {
        return 'Kayıt Ol';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Kayıt Ol';
    }

    public function getSubheading(): string | Htmlable
    {
        return 'Kayıt olmak için lütfen bilgilerinizi giriniz';
    }   
    
    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('Kayıt Ol'))
            ->size(Size::Large)
            ->icon('heroicon-o-arrow-right-on-rectangle')
            ->submit('authenticate');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'E-posta veya şifre hatalı.',
        ]);
    }



}
