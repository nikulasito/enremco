<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\Messages\MailMessage;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
public function boot(): void
{
    VerifyEmail::toMailUsing(function ($notifiable, $url) {
        $html = View::make('emails.registration-details', ['member' => $notifiable])->render();

        $pdf = PDF::loadHTML($html)
            ->setOption('encoding', 'utf-8')
            ->setPaper('A4', 'portrait');

        // ✅ Correct and valid return
        return (new MailMessage)
            ->subject('Thank You For Signing Up')
            ->greeting('Hello!')
            ->line('Thank you for registering. Please print the attached PDF and submit it to the office for approval.')
            ->line('If you have any questions, feel free to reach out.')
            ->attachData($pdf->output(), 'RegistrationDetails.pdf', [
                'mime' => 'application/pdf',
            ]);
    });
}
}
