<?php

namespace App\Http\Livewire\Page;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DecideHome extends Component
{
    protected $listeners = ['redirectToRoleHome'];

    public function render()
    {
        return view('livewire.page.decide-home');
    }

    public function redirectToRoleHome()
    {
        $sessionParams = session('user_data');

        $userRoleQuery = <<<SQL
            SELECT
                c.referencia
            FROM cargo c
            JOIN squad_usuario su
                ON c.id = su.cargo_id
            WHERE su.usuario_id = ?
                AND su.squad_id = ?
        SQL;

        $userRole = DB::selectOne(
            $userRoleQuery,
            [$sessionParams['usuario_id'], $sessionParams['squad_id']]
        )->referencia;

        $url = '/kanban'; // Caso seja Scrum Team

        if ($userRole == 'PO') $url = '/roadmap';
        if ($userRole == 'SM') $url = '/documentacoes';

        return redirect($url);
    }
}
