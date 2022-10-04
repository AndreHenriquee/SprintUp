<?php

namespace App\Http\Livewire\Src\Login;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LoginBody extends Component
{
    public $alias;
    public $routeParams;
    public $email, $senha;
    public $query;

    public function render()
    {
        return view('livewire.src.login.login-body');
    }

    public function userValidated()
    {
        $this->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        return true;
    }

    public function createSession()
    {
        $query = DB::table('usuario')
            ->join('squad_usuario', 'usuario.id', '=', 'squad_usuario.usuario_id')
            ->where('usuario.email', '=', $this->email)
            ->where('usuario.senha', '=', md5($this->senha))
            ->select(
                'usuario.id AS usuario_id',
                'squad_usuario.squad_id',
            )
            ->first();

        session([
            'user_data' => [
                'usuario_id' => $query->usuario_id,
                'squad_id' => $query->squad_id
            ],
        ]);

        if (isset($this->routeParams['hash_convite']) && !empty($this->routeParams['hash_convite'])) {
            return redirect('/aceitar-link-convite/' . trim($this->routeParams['hash_convite']));
        }

        return redirect('/kanban');
    }

    public function login()
    {
        if (self::userValidated()) {
            if (DB::table('usuario')
                ->where('email', $this->email)
                ->where('senha', md5($this->senha))
                ->exists()
            ) {
                self::createSession();
            } else {
                return back()->with('error', 'Não encontramos nenhum cadastro a essas credenciais');
            }
        }
    }
}
