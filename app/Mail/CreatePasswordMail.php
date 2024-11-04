<?php
// app/Mail/CreatePasswordMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatePasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        $link = url('password/create/' . $this->token); // Geração do link com o token

        return $this->view('emails.create_password')
            ->subject('Crie sua Senha')
            ->with(['link' => $link]);
    }
}
