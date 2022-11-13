<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $tokenToSend;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->tokenToSend = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Εγγραφή Νέου Χρήστη')
            ->view('emails.userVerifyEmail', ['tokenToSend' => $this->tokenToSend]);
    }
}
