<?php

namespace App\Http\Livewire\Src\Login;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginBody extends Component
{
    public $alias;
    public $email, $senha;
    public $query;

    public function render()
    {
        return view('livewire.src.login.login-body');
    }

    public function userValidated() {
        $this->validate([ 
            'email' => 'required|email', 
            'senha' => 'required', 
        ]); 
        return true;
    }

    public function createSession()
    {
        $query = DB::table('squad_usuario')
            ->join('usuario', 'squad_usuario.usuario_id', '=', 'usuario.id')
            ->where('usuario.email', '=', $this->email)
            ->where('usuario.senha', '=', md5($this->senha))
        ->first();

        session([
            'user_data' => [
                'usuario_id' => $query->usuario_id,
                'squad_id' => $query->squad_id
            ],
        ]);
        return redirect('/kanban');
    }

    public function login()
    {
        if(self::userValidated()){  
            if(DB::table('usuario')
                ->where('email', $this->email)
                ->where('senha', md5($this->senha))
                ->exists()) 
            {
                Self::createSession();
            } else {
                return back()->with('error', 'Não encontramos nenhum cadastro associado');
            }
        }        
    }
}
