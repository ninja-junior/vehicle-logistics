<?php

namespace App\Filament\Pages\Auth;

use Filament\Http\Livewire\Auth\Login as BasePage;
use Illuminate\Contracts\View\View;

class Login extends BasePage
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'admin@logistics.com',
            'password' => 'password',
            'remember' => true,
        ]);
    }

    public function render(): View
    {
        return view('filament::login')
            ->layout('filament::components.layouts.card', [
                'title' => __('Admin Login'),
            ]);
    }
}
