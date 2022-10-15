<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CardModal extends Component
{
    public $data;
    public $cardMentions;
    public $squadMemberId;
    public $squadMembers;
    public $squadMember;
    public $titulo;
    public $detalhamento;
    public $taskOwnerId;
    public $estimativeRadio;
    public $spValue;
    public $timeValue;
    public $prioridade;
    public $statusSelecionado;
    public $columns;

    public function render()
    {
        $this->cardMentions = self::fetchCardMentions((int) $this->data['id']);
        $this->squadMembers = self::fetchSquadMembers(session('user_data'));
        return view('livewire.src.kanban.card-modal');
    }

    private static function fetchCardMentions(int $cardId)
    {
        $mentionsQuery = <<<SQL
            SELECT
                m.id
                -- Tarefas
                , m.tarefa_origem_id
                , t.referencia AS tarefa_referencia
                , t.titulo AS tarefa_titulo
                , t.prioridade
                , t.coluna_id as tarefa_status
                -- Documentações
                , m.documentacao_origem_id
                , d.referencia AS documentacao_referencia
                , d.titulo AS documentacao_titulo
            FROM mencao m
            LEFT JOIN tarefa t
                ON m.tarefa_origem_id = t.id
            LEFT JOIN documentacao d
                ON m.documentacao_origem_id = d.id
            WHERE tarefa_mencionada_id = ?
                AND (
                    m.tarefa_origem_id IS NOT NULL
                    OR m.documentacao_origem_id IS NOT NULL
                )
                AND (
                    d.excluida <> 1
                    OR d.excluida IS NULL
                )
        SQL;

        $mentions = DB::select(
            $mentionsQuery,
            [$cardId]
        );

        $groupedMentions = [];

        foreach ($mentions as $mention) {
            if ($mention->tarefa_origem_id) {
                $groupedMentions['tarefas'][] = $mention;

                continue;
            }

            $groupedMentions['documentacoes'][] = $mention;
        }

        return $groupedMentions;
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

    public function updateCard() 
    {
        $estimativaId = null;
        $updateColumn = $this->statusSelecionado == null ? $this->data['id_coluna'] : $this->statusSelecionado;

        if($this->estimativeRadio && $this->spValue || $this->timeValue !== null) {
            $estimativa= self::setEstimatives();
            $forma = $estimativa[1] == "Sp" ? $forma = "Fibonacci" : $forma = "Horas";
            
            $estimativaId = DB::table('estimativa_tarefa')->insertGetId([
                'estimativa' => $estimativa[0],
                'forma' => $forma,
                'extensao' => $this->estimativeRadio,
            ]);
        }

        $this->taskOwnerId == 0 ? $this->taskOwnerId = null : $this->taskOwnerId;

        DB::table('tarefa')
            ->where('id', $this->data['id'])
            ->update([
                'titulo' => ucfirst($this->titulo),
                'responsavel_id' =>$this->taskOwnerId,
                'detalhamento' => $this->detalhamento,
                'data_hora_ultima_movimentacao' =>  Carbon::now('America/Sao_Paulo'),
                'prioridade' => $this->prioridade,
                'estimativa_tarefa_id' => $estimativaId,
                'coluna_id' => $updateColumn,
            ]);

        return redirect("/kanban");
    }

    public function setEstimatives() 
    {
        if ($this->estimativeRadio == "Sp") {
            $this->timeValue = null;
            $estimative = [$this->spValue, $this->estimativeRadio];
        } else {
            $this->spValue = null;
            $estimative = [$this->timeValue, $this->estimativeRadio];
        }
        return $estimative;
    }
}
