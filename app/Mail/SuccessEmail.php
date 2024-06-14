<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $transaction;

    public function __construct($user, $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yay! Pembayaran Berhasil!',
        );
    }

    public function build()
    {
        return $this->markdown('emails.payment-success')
                    ->subject('Yay! Pembayaran Berhasil!')
                    ->with([
                        'user' => $this->user,
                        'transaction' => $this->transaction,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
