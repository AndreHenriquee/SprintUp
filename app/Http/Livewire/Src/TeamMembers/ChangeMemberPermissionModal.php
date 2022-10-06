<?php

namespace App\Http\Livewire\Src\TeamMembers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ChangeMemberPermissionModal extends Component
{
    public $memberData, $teamId, $allowedGroups;
    public $permissionGroups;
    public $permissionGroupId;

    public function render()
    {
        $this->permissionGroups = self::fetchPermissionGroups($this->teamId);

        return view('livewire.src.team-members.change-member-permission-modal');
    }

    private static function fetchPermissionGroups(int $teamId)
    {
        $permissionGroupsQuery = <<<SQL
            SELECT
                id
                , nome
            FROM grupo_permissao
            WHERE equipe_id IS NULL
                OR equipe_id = ?
        SQL;

        return DB::select(
            $permissionGroupsQuery,
            [$teamId],
        );
    }

    private function dataValidated()
    {
        $this->validate([
            'permissionGroupId' => 'required|integer|gt:0'
        ]);

        return true;
    }

    public function changePermission()
    {
        if (self::dataValidated()) {
            DB::update(
                "UPDATE equipe_usuario SET grupo_permissao_id = ? WHERE equipe_id = ? AND usuario_id = ?",
                [$this->permissionGroupId, $this->teamId, $this->memberData['id']]
            );

            return redirect(request()->header('Referer'));
        }
    }
}
