<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getHeading(): string | HtmlString | null
    {
        return 'Super Admin Portal';
    }

    public function getSubheading(): string | HtmlString | null
    {
        return new HtmlString(
            'Sign in to manage the platform. '
            . '<a href="/" class="fi-admin-back-link">Back to main site</a>'
        );
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Sign In');
    }
}

