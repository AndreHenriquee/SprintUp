<?php

namespace App\Http\Livewire\Src\TeamMembers;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChangeMemberSquadsModal extends Component
{
    public $memberData, $teamId;
    public $scrumRoles, $teamSquads;
    public $selectedRoleIdsPerSquad, $isAtSquad;
    public $doesSquadRolesChanged;

    public function render()
    {
        $this->scrumRoles = self::fetchScrumRoles();
        $this->teamSquads = self::fetchTeamSquads($this->teamId, (int) $this->memberData['id']);

        return view('livewire.src.team-members.change-member-squads-modal');
    }

    private static function fetchScrumRoles()
    {
        return DB::select("SELECT id, nome FROM cargo");
    }

    private static function fetchTeamSquads(int $teamId, int $memberId)
    {
        $teamSquadsQuery = <<<SQL
            SELECT
                s.id
                , s.nome
                , (
                    SELECT
                        su.cargo_id
                    FROM squad_usuario su
                    WHERE su.usuario_id = ?
                        AND su.squad_id = s.id
                ) AS cargo_id
                , (
                    SELECT
                        c.nome
                    FROM squad_usuario su2
                    JOIN cargo c
                        ON su2.cargo_id = c.id
                    WHERE su2.usuario_id = ?
                        AND su2.squad_id = s.id
                ) AS cargo_nome
            FROM squad s
            WHERE s.equipe_id = ?
        SQL;

        return DB::select(
            $teamSquadsQuery,
            [$memberId, $memberId, $teamId]
        );
    }

    private function checkChangedRoles($changedRoles, $atSquadBeings)
    {
        $isEmptyChangedRoles = empty($changedRoles);
        $isEmptyAtSquadBeings = empty($atSquadBeings);

        if ($isEmptyChangedRoles && $isEmptyAtSquadBeings) return 0;

        if (!$isEmptyChangedRoles) {
            foreach ($changedRoles as $roleId) {
                if ((int) $roleId > 0) return 1;
            }
        }

        if (!$isEmptyAtSquadBeings) {
            foreach ($atSquadBeings as $atSquadBeing) {
                if (!$atSquadBeing) return 1;
            }
        }

        return 0;
    }

    private function dataValidated()
    {
        $this->doesSquadRolesChanged = $this->checkChangedRoles($this->selectedRoleIdsPerSquad, $this->isAtSquad);

        $this->validate([
            'doesSquadRolesChanged' => 'required|integer|gt:0'
        ]);

        return true;
    }

    public function changeRolesPerSquad()
    {
        if (self::dataValidated()) {
            if (!empty($this->selectedRoleIdsPerSquad)) {
                foreach ($this->selectedRoleIdsPerSquad as $squadId => $roleId) {
                    if ($roleId > 0) {
                        DB::table('squad_usuario')->insert([
                            'usuario_id' => (int) $this->memberData['id'],
                            'squad_id' => (int) $squadId,
                            'cargo_id' => (int) $roleId,
                        ]);
                    }
                }
            }

            if (!empty($this->isAtSquad)) {
                foreach ($this->isAtSquad as $squadId => $squadBeing) {
                    if (!$squadBeing) {
                        DB::delete(
                            "DELETE FROM squad_usuario WHERE usuario_id = ? AND squad_id = ?",
                            [(int) $this->memberData['id'], (int) $squadId]
                        );
                    }
                }
            }

            $remainingSquadAccessQuery = <<<SQL
                SELECT
                    s.id
                FROM squad s
                JOIN squad_usuario su
                    ON s.id = su.squad_id
                WHERE su.usuario_id = ?
                    AND s.equipe_id = ?
            SQL;

            $remainingSquadAccess = DB::select(
                $remainingSquadAccessQuery,
                [(int) $this->memberData['id'], (int) $this->teamId]
            );

            if (empty($remainingSquadAccess)) {
                DB::delete(
                    "DELETE FROM equipe_usuario WHERE usuario_id = ? AND equipe_id = ?",
                    [(int) $this->memberData['id'], (int) $this->teamId]
                );
            }

            return redirect(request()->header('Referer'));
        }
    }
}
