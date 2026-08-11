<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorAuthMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TripWise Admin Portal — Your 2FA Verification Code: ' . $this->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
            <div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #ffffff;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="color: #F44336; margin: 0;">TripWise.</h2>
                    <p style="color: #666666; font-size: 14px; margin-top: 5px;">Executive Command Center 2FA</p>
                </div>
                <div style="background-color: #fafafa; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #eeeeee;">
                    <p style="font-size: 14px; color: #333333; margin-bottom: 10px;">Your 6-digit 2FA login verification code is:</p>
                    <h1 style="font-size: 36px; letter-spacing: 6px; color: #1c1c1e; margin: 10px 0; font-weight: bold;">' . $this->code . '</h1>
                    <p style="font-size: 12px; color: #999999; margin-top: 10px;">This security code is valid for 10 minutes. Do not share this code with anyone.</p>
                </div>
                <p style="font-size: 12px; color: #aaaaaa; text-align: center; margin-top: 20px;">&copy; ' . date('Y') . ' TripWise Admin System. All rights reserved.</p>
            </div>
            ',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
