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
        session([
            'user_data' => [
                'usuario_id' => 2,
                'squad_id' => 2,
            ],
        ]);

        return redirect('/kanban');
    }
}
