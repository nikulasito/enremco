<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\SendgridMailer\Transport\SendgridApiTransport;

class MailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app(MailManager::class)->extend('sendgrid', function ($config) {
            return new SendgridTransport(env('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'));
        });
    }
}
