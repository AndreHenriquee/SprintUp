<?php

namespace App\Http\Livewire\Src\Kanban;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KanbanBody extends Component
{
    public $alias;
    public $data;
    public $columns;
    public $sessionParams;
    public $sprintDetails;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->data = self::fetchKanbanData(session('user_data'));
        $this->columns = self::fetchColumns((int) $this->data['id']);
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->sprintDetails = self::fetchSprintDetails(session('user_data'));
        return view('livewire.src.kanban.kanban-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        
        $teamQuery = <<<SQL
            SELECT e.id AS equipe_id
                , s.id AS squad_id
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
            WHERE s.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[BACKLOG] MNG_SQUAD_SPRINTS"
                AND p.permitido = 1
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $sessionParams['squad_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private static function fetchKanbanData(array $sessionParams)
    {
        $dataQuery = <<<SQL
            SELECT
                qk.id
                , qk.nome
                , qk.descricao
            FROM quadro_kanban qk
            JOIN squad_usuario su
                ON qk.squad_id = su.squad_id
            WHERE su.usuario_id = ?
                AND su.squad_id = ?;
        SQL;

        return (array) DB::selectOne(
            $dataQuery,
            [$sessionParams['usuario_id'], $sessionParams['squad_id']],
        );
    }

    private static function fetchColumns(int $quadroKanbanId)
    {
        $sessionParams = session('user_data');
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
                AND c.ordem <> 0
            ORDER BY c.ordem ASC;
        SQL;

        return DB::select(
            $columnsQuery,
            [$quadroKanbanId, $sessionParams['squad_id']],
        );
    }

    public function fetchSprintDetails(array $sessionParams)
    {
        $sprintQuery = <<<SQL
            SELECT
                sp.id
                , sp.numero
                , sp.inicio
                , sp.fim
                , sp.finalizada
                , GROUP_CONCAT(t.referencia SEPARATOR "; ") AS referencias 
            FROM sprint sp
            INNER JOIN squad sq
                ON sq.id = sp.squad_id
            INNER JOIN tarefa t
                ON t.sprint_id = sp.id
            WHERE sq.id = ?
                AND NOW() BETWEEN sp.inicio AND sp.fim
                AND sp.finalizada = 0
            GROUP BY sp.id, sp.numero, sp.inicio, sp.fim, sp.finalizada
        SQL;

        return DB::select(
            $sprintQuery,
            [$sessionParams['squad_id']],
        );
    }
}
