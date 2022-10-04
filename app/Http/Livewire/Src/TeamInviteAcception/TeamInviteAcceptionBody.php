<?php

namespace App\Http\Livewire\Src\TeamInviteAcception;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamInviteAcceptionBody extends Component
{
    protected $listeners = ['processInviteAcception'];

    public $routeParams;
    public $hashInformation, $isHashValid;

    public function render()
    {
        $this->hashInformation = $this->fetchHashInformation($this->routeParams['hash_convite']);

        return view('livewire.src.team-invite-acception.team-invite-acception-body');
    }

    private function fetchHashInformation(string $hash)
    {
        $hashValidationQuery = <<<SQL
            SELECT
                *
            FROM link_convite
            WHERE hash = ?
                AND ativo = 1
        SQL;

        $hashInformation = (array) DB::selectOne(
            $hashValidationQuery,
            [$hash]
        );

        $this->isHashValid = isset($hashInformation['id']);

        return $hashInformation;
    }

    private function validateHashEmail(int $userId)
    {
        if (!empty($this->hashInformation['emails_enviados'])) {
            $userEmail = DB::selectOne(
                "SELECT email FROM usuario WHERE id = ?",
                [$userId]
            )->email;

            return trim($this->hashInformation['emails_enviados']) == trim($userEmail);
        }

        return true;
    }

    private static function registerAccess(int $userId, array $hashInformation)
    {
        $teamId = DB::selectOne(
            "SELECT equipe_id FROM squad WHERE id = ?",
            [$hashInformation['squad_id']]
        )->equipe_id;

        $existingTeamAccess = DB::select(
            "SELECT * FROM equipe_usuario WHERE usuario_id = ? AND equipe_id = ?",
            [$userId, $teamId]
        );

        if (!isset($existingTeamAccess[0])) {
            DB::table('equipe_usuario')->insert([
                'usuario_id' => $userId,
                'equipe_id' => $teamId,
                'grupo_permissao_id' => $hashInformation['grupo_permissao_id'],
            ]);
        }

        $existingSquadAccess = DB::select(
            "SELECT * FROM squad_usuario WHERE usuario_id = ? AND squad_id = ?",
            [$userId, $hashInformation['squad_id']]
        );

        if (!isset($existingSquadAccess[0])) {
            DB::table('squad_usuario')->insert([
                'usuario_id' => $userId,
                'squad_id' => $hashInformation['squad_id'],
                'cargo_id' => $hashInformation['cargo_id'],
            ]);
        }
    }

    private static function createSession(int $userId, int $squadId)
    {
        session([
            'user_data' => [
                'usuario_id' => $userId,
                'squad_id' => $squadId,
            ],
        ]);
    }

    private static function deactivateInviteLink(int $inviteLinkId)
    {
        DB::update(
            "UPDATE link_convite SET ativo = 0 WHERE id = ?",
            [$inviteLinkId]
        );
    }

    public function processInviteAcception()
    {
        $sessionData = session('user_data');

        if ($sessionData && $sessionData['usuario_id']) {
            if ($this->validateHashEmail((int) $sessionData['usuario_id'])) {
                self::registerAccess(
                    (int) $sessionData['usuario_id'],
                    (array) $this->hashInformation
                );

                self::createSession(
                    (int) $sessionData['usuario_id'],
                    (int) $this->hashInformation['squad_id']
                );

                self::deactivateInviteLink((int) $this->hashInformation['id']);

                return redirect('/kanban');
            }

            $this->emit('alertWrongEmail');

            return;
        }

        return redirect('/login/' + $this->routeParams['hash_convite']);
    }
}
