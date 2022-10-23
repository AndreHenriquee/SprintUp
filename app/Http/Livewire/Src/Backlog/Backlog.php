<?php

namespace App\Http\Livewire\Src\Backlog;

use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Backlog extends Component
{
    public $sessionParams;
    public $columns;
    public $data;
    public $alias;
    public $backlogCards;
    public $routeParams;
    public $teamDataAndPermission;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->data = self::fetchKanbanData(session('user_data'));
        $this->columns = self::fetchColumns((int) $this->data['id']);
        return view('livewire.src.backlog.backlog');
    }

    public function fetchKanbanData() {
        $dataQuery = <<<SQL
            SELECT
                qk.id
                , qk.nome
                , qk.descricao
                , s.nome as squad_nome
            FROM quadro_kanban qk
            JOIN squad_usuario su
                ON qk.squad_id = su.squad_id
            JOIN squad s
                ON su.squad_id = s.id
            WHERE su.usuario_id = ?
                AND su.squad_id = ?;
        SQL;

        return (array) DB::selectOne(
            $dataQuery,
            [$this->sessionParams['usuario_id'], $this->sessionParams['squad_id']],
        );
    }

    public function fetchColumns(int $quadroKanbanId) {
        $columnsQuery = <<<SQL
             SELECT
                c.id
                , c.nome as nome
                , c.descricao as descricao
                , c.inicio_tarefa
                , c.fim_tarefa
                , c.wip
                , qk.squad_id as coluna_id
            FROM coluna c
            INNER JOIN quadro_kanban qk
                ON c.quadro_kanban_id = qk.id
            WHERE qk.id = ?
                AND qk.squad_id = ?
                AND c.ordem = 0
                AND c.inicio_tarefa = 1
            ORDER BY c.ordem ASC;
        SQL;

        return DB::select(
            $columnsQuery,
            [$quadroKanbanId, $this->sessionParams['squad_id']],
        );
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        
        $teamQuery = <<<SQL
            SELECT
                e.id as equipe_id
                , s.id as squad_id
                , su.usuario_id
                , e.nome
                , p.permitido AS permissao_gerenciar_backlog
                , c.referencia AS cargo
            FROM equipe e
            JOIN squad s
                ON e.id = s.equipe_id
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN squad_usuario su
                ON s.id = su.squad_id
                AND eu.usuario_id = su.usuario_id
            JOIN cargo c
                ON su.cargo_id = c.id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND s.id = ?
                AND su.usuario_id = ?
                AND tp.referencia = "[BACKLOG] MNG_SQUAD_SPRINTS"
                AND p.permitido = 1
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $this->sessionParams['squad_id'],
                $this->sessionParams['usuario_id'],
            ],
        );
    }


}
