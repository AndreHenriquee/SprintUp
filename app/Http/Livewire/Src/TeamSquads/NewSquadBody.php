<?php

namespace App\Http\Livewire\Src\TeamSquads;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewSquadBody extends Component
{
    public $routeParams;
    public $sessionParams, $teamDataAndPermission;
    public $nomeSquad, $descSquad;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);

        return view('livewire.src.team-squads.new-squad-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.nome
                , eu.grupo_permissao_id
                , p.permitido AS permissao_gerenciar_squads
            FROM equipe e
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[WORKFLOW] MNG_SQUADS"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'nomeSquad' => 'required|min:3|max:50',
                'descSquad' => 'max:250',
            ],
            [
                'nomeSquad.required' => 'Nome da squad é obrigatório',
                'nomeSquad.min' => 'Nome da squad precisa ter no mínio 3 caracteres',
                'nomeSquad.max' => 'Nome da squad precisa ter no máximo 50 caracteres',
                'descSquad.max' => 'Descrição da squad precisa ter no máximo 250 caracteres',
            ],
        );

        return true;
    }

    private function createRef(string $nomeSquad)
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

            $squadRef = strtoupper($referencia);
        } else {
            $squadRef = strtoupper($nomeSquad);
        }

        $numberOfSquadsWithSameRefQuery = <<<SQL
            SELECT
                COUNT(id) AS numero_squads
            FROM squad
            WHERE equipe_id = ?
                AND (
                    referencia = ?
                    OR referencia LIKE ?
                )
        SQL;

        $numberOfSquadsWithSameRef = (int) DB::selectOne(
            $numberOfSquadsWithSameRefQuery,
            [$this->routeParams['equipe_id'], $squadRef, $squadRef . '-%']
        )->numero_squads;

        if (!$numberOfSquadsWithSameRef) {
            return $squadRef;
        }

        return $squadRef . '-' . $numberOfSquadsWithSameRef + 1;
    }

    private function createBoard(int $squadId)
    {
        $kanbanId = DB::table('quadro_kanban')->insertGetId([
            'nome' => 'Quadro Kanban | ' . $this->nomeSquad,
            'squad_id' => $squadId,
        ]);

        DB::table('coluna')->insert([
            ['nome' => "Backlog", 'ordem' => 0, 'inicio_tarefa' => 0, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId],
            ['nome' => "To Do", 'ordem' => 1, 'inicio_tarefa' => 1, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId],
            ['nome' => "Doing", 'ordem' => 2, 'inicio_tarefa' => 0, 'fim_tarefa' => 0, 'quadro_kanban_id' => $kanbanId],
            ['nome' => "Done", 'ordem' => 3, 'inicio_tarefa' => 0, 'fim_tarefa' => 1, 'quadro_kanban_id' => $kanbanId]
        ]);
    }

    public function registerSquad()
    {
        if ($this->dataValidated()) {
            $squadId = DB::table('squad')->insertGetId([
                'nome' => $this->nomeSquad,
                'descricao' => $this->descSquad,
                'referencia' => $this->createRef($this->nomeSquad),
                'equipe_id' => $this->routeParams['equipe_id']
            ]);

            $this->createBoard($squadId);

            DB::table('squad_usuario')->insert([
                'usuario_id' => $this->sessionParams['usuario_id'],
                'squad_id' => $squadId,
                'cargo_id' => 3,
            ]);

            return redirect('/squads-equipe/' . $this->routeParams['equipe_id']);
        }
    }

    public function resetFields()
    {
        $this->nomeSquad = '';
        $this->descSquad = '';
    }
}
