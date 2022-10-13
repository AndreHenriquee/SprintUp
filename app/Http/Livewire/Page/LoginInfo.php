<?php

namespace App\Http\Livewire\Page;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoginInfo extends Component
{
    public $loginInfo;

    public function render()
    {
        $this->loginInfo = self::fetchLoginInfo(session('user_data'));

        return view('livewire.page.login-info');
    }

    private static function fetchLoginInfo(array $sessionParams)
    {
        $loggedUserQuery = <<<SQL
            SELECT
                nome
                , foto
            FROM usuario
            WHERE id = ?
        SQL;

        $selectedSquadQuery = <<<SQL
            SELECT
                nome
                , referencia
                , logo
            FROM squad
            WHERE id = ?
                AND (
                    excluida <> 1
                    OR excluida IS NULL
                )
        SQL;

        $loggedUserInfo = (array) DB::selectOne(
            $loggedUserQuery,
            [$sessionParams['usuario_id']],
        );

        $selectedSquadInfo = (array) DB::selectOne(
            $selectedSquadQuery,
            [$sessionParams['squad_id']],
        );

        return [
            'usuario' => [
                'nome' => $loggedUserInfo['nome'],
                'foto' => $loggedUserInfo['foto'],
            ],
            'squad' => [
                'nome' => $selectedSquadInfo['nome'],
                'referencia' => $selectedSquadInfo['referencia'],
                'foto' => $selectedSquadInfo['logo'],
            ],
        ];
    }

    public function logout()
    {
        session([
            'user_data' => [
                'usuario_id' => null,
                'squad_id' => null,
            ],
        ]);

        return redirect('/login');
    }
}
