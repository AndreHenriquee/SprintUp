<?php

namespace App\Http\Livewire\Src\TeamMembers;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChangeMemberRoleModal extends Component
{
    public $memberData, $teamId, $allowedGroups;
    public $scrumRoles, $memberSquads;
    public $selectedRoleIdsPerSquad;
    public $doesRolesChanged;

    public function render()
    {
        $this->scrumRoles = self::fetchScrumRoles();
        $this->memberSquads = self::fetchMemberSquads($this->teamId, (int) $this->memberData['id']);

        return view('livewire.src.team-members.change-member-role-modal');
    }

    private static function fetchScrumRoles()
    {
        return DB::select("SELECT id, nome FROM cargo");
    }

    private static function fetchMemberSquads(int $teamId, int $memberId)
    {
        $memberSquadsQuery = <<<SQL
            SELECT
                s.id
                , s.nome
                , su.cargo_id
            FROM squad s
            JOIN squad_usuario su
                ON s.id = su.squad_id
            WHERE su.usuario_id = ?
                AND s.equipe_id = ?
        SQL;

        return DB::select(
            $memberSquadsQuery,
            [$memberId, $teamId]
        );
    }

    private function checkChangedRoles($changedRoles)
    {
        if (empty($changedRoles)) return 0;

        foreach ($changedRoles as $roleId) {
            if ((int) $roleId > 0) return 1;
        }

        return 0;
    }

    private function dataValidated()
    {
        $this->doesRolesChanged = $this->checkChangedRoles($this->selectedRoleIdsPerSquad);

        $this->validate([
            'doesRolesChanged' => 'required|integer|gt:0'
        ]);

        return true;
    }

    public function changeScrumRoles()
    {
        if (self::dataValidated()) {
            foreach ($this->selectedRoleIdsPerSquad as $squadId => $roleId) {
                if ($roleId > 0) {
                    $updateRoleQuery = <<<SQL
                        UPDATE
                            squad_usuario
                        SET cargo_id = ?
                        WHERE usuario_id = ?
                            AND squad_id = ?
                    SQL;

                    DB::update(
                        $updateRoleQuery,
                        [
                            (int) $roleId,
                            (int) $this->memberData['id'],
                            (int) $squadId,
                        ]
                    );
                }
            }

            return redirect(request()->header('Referer'));
        }
    }
}
