<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dompdf\Dompdf;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $pdfContent;

    public function __construct($member, $pdfContent)
    {
        $this->member = $member;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Your Registration Details')
            ->view('emails.registration')
            ->with(['member' => $this->member])
            ->attachData($this->pdfContent, 'registration-details.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
