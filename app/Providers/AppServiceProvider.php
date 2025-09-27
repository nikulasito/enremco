<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Show awaiting approval count in admin nav
        View::composer('layouts.admin-navigation', function ($view) {
            $newMembersCount = User::where('status', 'Awaiting Approval')->count();
            $view->with('newMembersCount', $newMembersCount);
        });

        // Register SendGrid API transport
        // $apiKey = config('services.sendgrid.api_key');

        // if (!$apiKey) {
        //     throw new \RuntimeException('SendGrid API key is not set.');
        // }

        // app(MailManager::class)->extend('sendgrid', function () use ($apiKey) {
        //     return new SendgridApiTransport($apiKey);
        // });
    }
}
