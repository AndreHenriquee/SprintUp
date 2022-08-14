<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentacoesBody extends Component
{
    
    protected $alias;
    public $routeParams;
    public $documents;

    public function render()
    {

        $this->squadData = self::fetchSquadData(
            $this->routeParams['squad_id'] ?? null,
            session('user_data')
        ); 
        
        $this->documents = self::fetchDocuments($this->squadData['id'], session('user_data'));

        return view('livewire.src.documentacoes.documentacoes-body');
    }    

    public function updateFilter()
    {
        if (!(int) $this->selectedDocumentId) {
            return redirect('/' . $this->alias);
        }

        if ($this->alias == 'documentacao') {
            return redirect(
                '/' . implode('/', [$this->alias, $this->selectedDocumentId])
            );
        }

        return redirect(
            '/' . implode('/', [$this->alias, $this->teamData['id'], $this->selectedDocumentId])
        );
    }

    public function fetchDocuments(int $squad_id, array $sessionParams) {
        
        $filter = '';

        if ($this->routeParams['documentacao_tipo']) {
            $filter .= <<<SQL
                AND d.tipo = ?
            SQL;

            $params[] = $this->routeParams['documentacao_tipo'];
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
            WHERE d.squad_id = ?
                OR (
                    d.squad_id IS NULL
                    AND d.equipe_id = s.equipe_id
                )
            {$filter}
            ORDER BY data_hora DESC
        SQL;
        
        $documents = DB::select(
            $documentsQuery,
            [$sessionParams['squad_id'], $sessionParams['squad_id']], $params
        );

        return $documents;
    }

    private static function fetchSquadData($squadId, array $sessionParams)
    {

        if (!$squadId) {
            $teamBySquadQuery = <<<SQL
                SELECT
                    e.id
                    , e.nome
                FROM equipe e
                JOIN squad s
                    ON e.id = s.equipe_id
                    AND s.id = ?
            SQL;

            return (array) DB::selectOne(
                $teamBySquadQuery,
                [$sessionParams['squad_id']],
            );
        }

        $teamByIdQuery = <<<SQL
            SELECT
                id
                , nome
            FROM equipe
            WHERE id = ?
        SQL;

        return (array) DB::selectOne(
            $teamByIdQuery,
            [$squadId],
        );
    }

}
