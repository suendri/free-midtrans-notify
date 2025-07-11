<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MidtransCallbackNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $orderId;
    public $targetUrl;
    public $payload;

    /**
     * Create a new message instance.
     */
    public function __construct(string $orderId, ?string $targetUrl, array $payload)
    {
        $this->orderId = $orderId;
        $this->targetUrl = $targetUrl;
        $this->payload = $payload;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Delitekno Midtrans Callback: ' . $this->orderId)
            ->view('emails.midtrans-callback')
            ->with([
                'orderId' => $this->orderId,
                'targetUrl' => $this->targetUrl,
                'payload' => $this->payload,
            ]);
    }
}
