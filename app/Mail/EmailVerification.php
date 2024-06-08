<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;

    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    public function build()
    {
        // return otp and user
        return $this->markdown('emails.verification')
            ->subject('Verifikasi Email GASCPNS')
            ->with([
                'otp' => $this->otp,
                'user' => $this->user
            ]);
    }
}
