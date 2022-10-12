<?php

namespace App\Http\Livewire\Page;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SquadList extends Component
{
    public $alias;
    public $sessionParams;
    public $teamsAndSquads;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamsAndSquads = self::fetchUserSquads($this->sessionParams);

        return view('livewire.page.squad-list');
    }

    private static function fetchUserSquads(array $sessionParams)
    {
        $squadsQuery = <<<SQL
            SELECT
                s.id AS squad_id
                , s.nome AS squad_nome
                , e.id AS equipe_id
                , e.nome AS equipe_nome
            FROM squad s
            JOIN equipe e
                ON s.equipe_id = e.id
            JOIN squad_usuario su
                ON s.id = su.squad_id
                AND su.usuario_id = ?
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
                AND eu.usuario_id = ?
            WHERE (
                s.excluida <> 1
                OR s.excluida IS NULL
            )
            ORDER BY e.nome, s.nome
        SQL;

        $squads = DB::select(
            $squadsQuery,
            [$sessionParams['usuario_id'], $sessionParams['usuario_id']],
        );

        $groupedSquads = [];

        foreach ($squads as $squad) {
            if (!isset($groupedSquads[$squad->equipe_id]['nome'])) {
                $groupedSquads[$squad->equipe_id]['nome'] = $squad->equipe_nome;
            }

            $groupedSquads[$squad->equipe_id]['squads'][$squad->squad_id] = $squad->squad_nome;
        }

        return $groupedSquads;
    }

    public function changeSquad(int $squadId)
    {
        if ($squadId == $this->sessionParams['squad_id'])
            return;

        session([
            'user_data' => [
                'usuario_id' => $this->sessionParams['usuario_id'],
                'squad_id' => $squadId,
            ],
        ]);

        return redirect('/' . $this->alias);
    }
}
