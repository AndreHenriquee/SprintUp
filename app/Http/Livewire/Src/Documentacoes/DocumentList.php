<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentList extends Component
{
    public $documentacoes;
    public $tipo;
    public $typeMap;

    public function render()
    {
        $this->documentacoes = self::fetchDocuments(session('user_data'));

        return view('livewire.src.documentacoes.document-list');
    }

    private static function fetchDocuments(array $sessionParams)
    {
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
            WHERE d.squad_id = ?
                OR (
                    d.squad_id IS NULL
                    AND d.equipe_id = s.equipe_id
                )
            ORDER BY data_hora DESC
        SQL;

        $documents = DB::select(
            $documentsQuery,
            [$sessionParams['squad_id'], $sessionParams['squad_id']],
        );

        $groupedDocuments = [];

        foreach ($documents as $document) {
            $groupedDocuments[$document->tipo][] = $document;
        }

        return $groupedDocuments;
    }
}
