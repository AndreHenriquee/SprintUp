<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentList extends Component
{
    public $filters;
    public $teamDataAndPermission;

    public $documentacoes;
    public $tipo;
    public $typeMap;
    public $hasFilters = false;

    public function render()
    {
        $this->documentacoes = $this->fetchDocuments(session('user_data'));

        return view('livewire.src.documentacoes.document-list');
    }

    private function fetchDocuments(array $sessionParams)
    {
        $filters = '';
        $mentionJoin = '';

        $params[] = $sessionParams['squad_id'];

        if ($this->filters['textFilter']) {
            $textFilter = '"%' . $this->filters['textFilter'] . '%"';

            $filters .= <<<SQL
                AND (
                    d.titulo LIKE $textFilter
                    OR d.conteudo LIKE $textFilter
                    OR d.referencia LIKE $textFilter
                )
            SQL;

            $this->hasFilters = true;
        }

        if ($this->filters['taskMentionIdFilter'] || $this->filters['memberMentionIdFilter']) {
            $mentionJoin .= <<<SQL
                JOIN mencao m
                    ON d.id = m.documentacao_origem_id
                    AND (
            SQL;

            if ($this->filters['taskMentionIdFilter']) {
                $mentionJoin .= <<<SQL
                    m.tarefa_mencionada_id = ?
                SQL;

                $params[] = $this->filters['taskMentionIdFilter'];
            }

            if ($this->filters['taskMentionIdFilter'] && $this->filters['memberMentionIdFilter']) {
                $mentionJoin .= ' OR ';
            }

            if ($this->filters['memberMentionIdFilter']) {
                $mentionJoin .= <<<SQL
                    m.usuario_mencionado_id = ?
                SQL;

                $params[] = $this->filters['memberMentionIdFilter'];
            }

            $mentionJoin .= ')';

            $this->hasFilters = true;
        }

        if ($this->filters['dateFilter']) {
            $filters .= <<<SQL
                AND d.data_hora BETWEEN ? AND ?
            SQL;

            $params[] = $this->filters['dateFilter'] . ' 00:00:00';
            $params[] = $this->filters['dateFilter'] . ' 23:59:59';

            $this->hasFilters = true;
        }

        $documentsQuery = <<<SQL
            SELECT
                d.id
                -- Pré-visualização
                , d.referencia
                , d.titulo
                , d.data_hora
                -- Detalhamento da documentação
                , d.tipo
                , d.conteudo
            FROM documentacao d
            JOIN squad s
                ON s.id = ?
            {$mentionJoin}
            WHERE (
                d.squad_id = s.id
                OR (
                    d.squad_id IS NULL
                    AND d.equipe_id = s.equipe_id
                )
            )
                AND (
                    s.excluida <> 1
                    OR s.excluida IS NULL
                )
                AND (
                    d.excluida <> 1
                    OR d.excluida IS NULL
                )
            {$filters}
            GROUP BY d.id, d.referencia, d.titulo, d.data_hora, d.tipo, d.conteudo
            ORDER BY d.data_hora DESC
        SQL;

        $documents = DB::select(
            $documentsQuery,
            $params,
        );

        $groupedDocuments = [];

        foreach ($documents as $document) {
            $groupedDocuments[$document->tipo][] = $document;
        }

        return $groupedDocuments;
    }
}
