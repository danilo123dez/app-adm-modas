<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class esqueciMinhaSenha extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Esqueci Minha Senha');

        $this->from('admmoda@noreply.com.br', 'Administração Moda');

        return $this->view('mail.email_recuperacao_senha_adm', [
            'user' => $this->user,
            'message' => $this
        ]);
    }
}
