<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateCard extends Component
{
    public $data;
    public $taskMentionIdFilter;
    public $squadMemberId;
    public $squadMembers;
    public $squadMember;
    public $descricao;
    public $titulo;
    public $taskOwnerId;
    public $userInfo;

    public function render()
    {
        $this->squadMembers = self::fetchSquadMembers(session('user_data'));
        $this->userInfo = self::fetchUserInfo(session('user_data'));
        return view('livewire.src.kanban.create-card');
    }

    public function createCard()
    {
        $sessionParams = session('user_data');
        $contador = self::referenceCounter();

        if (self::fieldsValidation()) {
            $taskInfo = DB::table('squad')
                ->where('squad.id', '=', $sessionParams['squad_id'])
                ->select('squad.referencia as referencia')
                ->first();

            DB::table('tarefa')->insert([
                'referencia' => $taskInfo->referencia . $contador,
                'titulo' => $this->titulo,
                'detalhamento' => $this->descricao,
                'prioridade' => 1,
                'data_hora_criacao' => Carbon::now('America/Sao_Paulo'),
                'data_hora_ultima_movimentacao' => Carbon::now('America/Sao_Paulo'),
                'coluna_id' => 1,
                'squad_id' => $sessionParams['squad_id'],
                'responsavel_id' => $this->taskOwnerId,
                'relator_id' => $sessionParams['usuario_id'],
            ]);

            return redirect('/kanban');
        }
    }

    public function referenceCounter()
    {
        $sessionParams = session('user_data');
        $referencia = "";

        $referenciaQuery =
            DB::table('tarefa')
            ->select('referencia')
            ->where('squad_id', '=', $sessionParams['squad_id'])
            ->orderByDesc('referencia')
            ->limit(1);

        if (!$referenciaQuery->count() > 0) {
            $referencia .= "-1";
        } else {
            $stringArray = explode("-", $referenciaQuery->first()->referencia);
            $contador = $stringArray[1] + 1;
            $referencia = "-" . $contador;
        }
        return $referencia;
    }

    private static function fetchSquadMembers(array $sessionParams)
    {
        $squadMembersQuery = <<<SQL
            SELECT
                u.id
                , u.nome
                , u.email
            FROM usuario u
            JOIN squad_usuario su
                ON u.id = su.usuario_id
                AND su.squad_id = ?
            ORDER BY u.nome ASC
        SQL;

        return (array) DB::select(
            $squadMembersQuery,
            [$sessionParams['squad_id']],
        );
    }

    private static function fetchUserInfo(array $sessionParams)
    {
        $userInfoQuery = <<<SQL
            SELECT
                nome
                , foto
            FROM usuario
            WHERE id = ?
        SQL;

        $userInfo = (array) DB::selectOne(
            $userInfoQuery,
            [$sessionParams['usuario_id']],
        );

        return [
            'usuario' => [
                'nome' => $userInfo['nome'],
            ],
        ];
    }

    public function fieldsValidation()
    {
        $this->validate(
            [
                'titulo' => 'required|max:150',
            ],
            [
                'titulo.required' => 'Título da tarefa é obrigatório',
                'titulo.max' => 'Titulo deve conter até 150 caracteres'
            ],
        );

        return true;
    }
}
