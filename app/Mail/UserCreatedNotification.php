<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $passwordGenerado;

    public function __construct($name, $email, $passwordGenerado)
    {
        $this->name = $name;
        $this->email = $email;
        $this->passwordGenerado = $passwordGenerado;
    }

    public function build()
    {
        return $this->subject('Bienvenido a Emilima - Acceso a tu cuenta de compras')
                    ->view('emails.user_created')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'passwordGenerado' => $this->passwordGenerado,
                    ]);
    }
}
