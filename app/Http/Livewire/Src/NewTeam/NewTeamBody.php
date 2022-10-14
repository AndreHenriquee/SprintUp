<?php

namespace App\Http\Livewire\Src\NewTeam;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewTeamBody extends Component
{
    public $nomeEquipe, $descEquipe, $roadmapCheckbox = false;
    public $nomeSquad, $descSquad;

    public function render()
    {
        return view('livewire.src.new-team.new-team-body');
    }

    public function fieldsValidation()
    {
        $this->validate(
            [
                'nomeEquipe' => 'required|min:3|max:50|unique:equipe,nome',
                'descEquipe' => 'max:250',
                'nomeSquad' => 'required|min:3|max:50',
                'descSquad' => 'max:250',
            ],
            [
                'nomeEquipe.required' => 'Nome da equipe é obrigatório',
                'nomeEquipe.min' => 'Nome da equipe precisa ter no mínimo 3 caracteres',
                'nomeEquipe.max' => 'Nome da equipe precisa ter no máximo 50 caracteres',
                'nomeEquipe.unique' => 'Nome da equipe já existe',
                'descEquipe.max' => 'Descrição da equipe precisa ter no máximo 250 caracteres',
                'nomeSquad.required' => 'Nome da squad é obrigatório',
                'nomeSquad.min' => 'Nome da squad precisa ter no mínimo 3 caracteres',
                'nomeSquad.max' => 'Nome da squad precisa ter no máximo 50 caracteres',
                'descSquad.max' => 'Descrição da squad precisa ter no máximo 250 caracteres',
            ],
        );

        return true;
    }

    private static function createRef(String $nomeSquad)
    {
        if (
            strpos($nomeSquad, ' ')
            || strpos($nomeSquad, ',')
            || strpos($nomeSquad, '_')
            || strpos($nomeSquad, '-')
        ) {
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
        $kanbanId = DB::table('quadro_kanban')->insertGetId([
            'nome' => 'Quadro Kanban | ' . $this->nomeSquad,
            'squad_id' => $squadId,
        ]);

        DB::table('coluna')->insert(array(
            array('nome' => "To Do", 'ordem' => 1, 'inicio_tarefa' => 1, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId),
            array('nome' => "Doing", 'ordem' => 2, 'inicio_tarefa' => 0, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId),
            array('nome' => "Done", 'ordem' => 3, 'inicio_tarefa' => 0, 'fim_tarefa' => 1, 'quadro_kanban_id' => $kanbanId)
        ));
    }

    public function registerTeam()
    {
        if (self::fieldsValidation()) {
            $equipeId = DB::table('equipe')->insertGetId([
                'nome' => $this->nomeEquipe,
                'descricao' => $this->descEquipe,
                'roadmap_ativo' => $this->roadmapCheckbox
            ]);

            $squadId = DB::table('squad')->insertGetId([
                'nome' => $this->nomeSquad,
                'descricao' => $this->descSquad,
                'referencia' => self::createRef($this->nomeSquad),
                'equipe_id' => $equipeId
            ]);

            $this->createBoard($squadId);

            $userId = session('user_data')['usuario_id'];

            DB::table('squad_usuario')->insert([
                'usuario_id' => $userId,
                'squad_id' => $squadId,
                'cargo_id' => 3,
            ]);

            DB::table('equipe_usuario')->insert([
                'usuario_id' => $userId,
                'equipe_id' => $equipeId,
                'grupo_permissao_id' => 1,
            ]);

            return redirect('/equipes');
        }
    }

    public function resetFields()
    {
        $this->nomeEquipe = '';
        $this->descEquipe = '';
        $this->roadmapCheckbox = false;
        $this->nomeSquad = '';
        $this->descSquad = '';
    }
}
