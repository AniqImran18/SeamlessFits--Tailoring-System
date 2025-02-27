<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DiscountNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Congratulations! You’ve Earned a 10% Discount')
                    ->view('email.discount-notification');
    }
}