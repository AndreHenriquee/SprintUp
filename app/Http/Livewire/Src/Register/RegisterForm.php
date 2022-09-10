<?php

namespace App\Http\Livewire\Src\Register;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RegisterForm extends Component
{

    public $email, $senha, $senha_confirmation, $nome, $data_nascimento;
    public $equipeId, $nomeEquipe, $descTime, $roadmapCheckbox = false;
    public $nomeSquad, $descSquad;

    public function render()
    {
        return view('livewire.src.register.register-form');
    }

    public function resetFields()
    {
        $this->email = '';
        $this->senha = '';
        $this->senha_confirmation = '';
        $this->nome = '';
        $this->data_nascimento = '';
        $this->nomeEquipe = '';
        $this->descTime = '';
        $this->roadmapCheckbox = false;
        $this->nomeSquad = '';
        $this->descSquad = '';
    }

    public function fieldsValidation()
    {
        $this->validate(
            [
                'nome' => 'required',
                'email' => 'required|email:rfc,dns|unique:usuario,email',
                'senha' => 'required|confirmed|min:8',
                'data_nascimento' => 'required|before_or_equal:01/01/2005',
                'nomeEquipe' => 'required|unique:equipe,nome',
                'nomeSquad' => 'required'
            ],
            [
                'nomeSquad.required' => 'Nome da squad é obrigatório',
                'nomeEquipe.unique' => 'Nome da equipe já existe',
                'nomeEquipe.required' => 'Nome da equipe é obrigatório',
                'data_nascimento.before_or_equal' => 'Data de nascimento deve ser menor que 2005',
                'data_nascimento.required' => 'Data de nascimento é obrigatória',
                'email.unique' => 'E-mail já existe',
                'email.required' => 'E-mail é obrigatório',
                'email.email' => 'E-mail em formato inválido',
                'senha.required' => 'Senha é obrigatório',
                'senha.confirmed' => 'A senha e a sua confirmação não são idênticas',
                'senha.min' => 'Senha deve ser maior que 8 caracteres'
            ],
        );

        return true;
    }

    public function createSession(Int $usuario_id, Int $squad_id)
    {
        session([
            'user_data' => [
                'usuario_id' => $usuario_id,
                'squad_id' => $squad_id,
            ],
        ]);
    }

    private static function createRef(String $nomeSquad)
    {
        if (strpos($nomeSquad, " ")) {
            $nomeSquad = preg_split("/[\s,_-]+/", $nomeSquad);

            $referencia = '';

            foreach ($nomeSquad as $letra) {
                $referencia .= mb_substr($letra, 0, 1);
            }

            return strtoupper($referencia);
        } else {
            return strtoupper($nomeSquad);
        }
    }

    private function createBoard(Int $squadId)
    {
        $board = [
            $kanbanId = DB::table('quadro_kanban')->insertGetId([
                'nome' => 'Quadro Kanban | ' . $this->nomeSquad,
                'squad_id' => $squadId,
            ]),

            DB::table('coluna')->insert(array(
                array('nome' => "To Do", 'ordem' => 1, 'inicio_tarefa' => 1, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId),
                array('nome' => "Doing", 'ordem' => 2, 'inicio_tarefa' => 0, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId),
                array('nome' => "Done", 'ordem' => 3, 'inicio_tarefa' => 0, 'fim_tarefa' => 1, 'quadro_kanban_id' => $kanbanId)
            )),
        ];
        return $board;
    }

    public function registerUser()
    {
        if (self::fieldsValidation()) {
            $usuarioId = DB::table('usuario')->insertGetId([
                'nome' => $this->nome,
                'email' => $this->email,
                'senha' => md5($this->senha),
                'data_nascimento' => $this->data_nascimento
            ]);

            $equipeId = DB::table('equipe')->insertGetId([
                'nome' => $this->nomeEquipe,
                'descricao' => $this->descTime,
                'roadmap_ativo' => $this->roadmapCheckbox
            ]);

            $squadRef = self::createRef($this->nomeSquad);

            $squadId = DB::table('squad')->insertGetId([
                'nome' => $this->nomeSquad,
                'descricao' => $this->descSquad,
                'referencia' => $squadRef,
                'equipe_id' => $equipeId
            ]);

            $this->createBoard($squadId);

            DB::table('squad_usuario')->insert([
                'usuario_id' => $usuarioId,
                'squad_id' => $squadId,
                'cargo_id' => 3,
            ]);

            DB::table('equipe_usuario')->insert([
                'usuario_id' => $usuarioId,
                'equipe_id' => $equipeId,
                'grupo_permissao_id' => 1,
            ]);

            self::createSession($usuarioId, $squadId);

            return redirect('/kanban');
        }
    }
}
