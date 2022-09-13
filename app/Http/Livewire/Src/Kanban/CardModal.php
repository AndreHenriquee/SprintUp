<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CardModal extends Component
{
    public $data;
    public $cardMentions;

    public function render()
    {
        $this->cardMentions = self::fetchCardMentions((int) $this->data['id']);
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
}
