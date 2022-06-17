<?php

namespace App\Http\Livewire\Src\Login;

use Livewire\Component;

class LoginBody extends Component
{
    protected $listeners = ['mockLogin' => 'login'];

    public function render()
    {
        return view('livewire.src.login.login-body');
    }

    public function login()
    {
        session_start();

        $_SESSION['usuario_id'] = 2;
        $_SESSION['squad_id'] = 2;

        return redirect('/kanban');
    }
}
