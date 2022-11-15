<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $emailInfo)
    {
        $this->emailInfo = $emailInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.inviteLinkEmail')
            ->from('sprintup@gmail.com')
            ->subject('Você foi convidado para uma equipe na Sprint Up!')
            ->with([
                'equipe_nome' => $this->emailInfo['equipe_nome'],
                'squad_nome' => $this->emailInfo['squad_nome'],
                'cargo_nome' => $this->emailInfo['cargo_nome'],
                'grupo_permissao_nome' => $this->emailInfo['grupo_permissao_nome'],
                'email' => $this->emailInfo['email'],
                'hash' => $this->emailInfo['hash'],
                'root_link' => $this->emailInfo['root_link'],
            ]);
    }
}
