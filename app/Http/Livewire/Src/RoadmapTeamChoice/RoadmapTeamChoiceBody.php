<?php

namespace App\Http\Livewire\Src\RoadmapTeamChoice;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoadmapTeamChoiceBody extends Component
{
    public $teamsList;
    public $teamId;
    public $selectedTeamData;

    public function render()
    {
        $this->teamsList = self::fetchTeams();

        return view('livewire.src.roadmap-team-choice.roadmap-team-choice-body');
    }

    private static function fetchTeams()
    {
        $teamsListQuery = <<<SQL
            SELECT
                e.id
                , e.nome
                , e.descricao
                , COUNT(DISTINCT p.id) AS numero_produtos
                , COUNT(f.id) AS numero_funcionalidades
            FROM equipe e
            JOIN produto p
                ON e.id = p.equipe_id
            LEFT JOIN funcionalidade f
                ON p.id = f.produto_id
            WHERE e.roadmap_ativo = 1
                AND (
                    p.excluido <> 1
                    OR p.excluido IS NULL
                )
                AND (
                    f.excluida <> 1
                    OR f.excluida IS NULL
                )
            GROUP BY e.id, e.nome, e.descricao
            ORDER BY e.nome ASC
        SQL;

        return (array) DB::select($teamsListQuery);
    }

    private function dataValidated()
    {
        $this->validate(['teamId' => 'required|not_in:null']);

        return true;
    }

    public function teamPreview()
    {
        if ($this->dataValidated()) {
            $selectedTeamPosition = array_search(
                $this->teamId,
                array_column($this->teamsList, 'id')
            );

            $this->selectedTeamData = $this->teamsList[$selectedTeamPosition];
        }
    }

    public function goToTeamRoadmap()
    {
        if ($this->dataValidated()) {
            return redirect('/roadmap-cliente/' . $this->teamId);
        }
    }
}
