<?php

namespace App\Http\Livewire\Src\Backlog;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListSprints extends Component
{
    public $teamDataAndPermission;
    public $sessionParams;
    public $routeParams;
    public $sprints;
    public $seletcedToEnd;
    public $currentTime;
    
    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->sprints = self::fetchSprints($this->sessionParams);
        $this->currentTime = Carbon::now('America/Sao_Paulo')->format('Y-m-d');
        return view('livewire.src.backlog.list-sprints');
    }

    public function mount()
    {
        $this->seletcedToEnd = $this->sprints;
    }
    
    public function endSprint(int $id)
    {
        DB::table('sprint')
            ->where('id', $id)
            ->update(['finalizada' => 1]);
            
        return;
    }

    public function fetchSprints(array $sessionParams)
    {
        $sprintQuery = <<<SQL
            SELECT
                sp.id
                , sp.numero
                , sp.inicio
                , sp.fim
                , sp.finalizada
                , (sp.fim - sp.inicio) AS dias
                , GROUP_CONCAT(t.referencia SEPARATOR "; ") AS referencias
            FROM sprint sp
            INNER JOIN squad sq
                ON sq.id = sp.squad_id
            INNER JOIN tarefa t
                ON t.sprint_id = sp.id
            WHERE sq.id = ?
                AND t.excluida = 0
            GROUP BY sp.id, sp.numero, sp.inicio, sp.fim, sp.finalizada
            ORDER BY sp.inicio DESC
        SQL;

        return DB::select(
            $sprintQuery,
            [$sessionParams['squad_id']],
        );
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        
        $teamQuery = <<<SQL
            SELECT
                e.id AS equipe_id
                , s.id AS squad_id
                , s.nome AS squad_nome
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