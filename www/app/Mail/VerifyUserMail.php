<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $api_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $api_token)
    {
        $this->name      = $name;
        $this->api_token = $api_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail_verification')
            ->with([
                'api_token' => $this->api_token,
                'name'      => $this->name,
            ]);
    }
}
