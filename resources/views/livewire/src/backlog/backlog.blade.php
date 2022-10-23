<div class="container">
    @if(isset($teamDataAndPermission['equipe_id']))
        @if($teamDataAndPermission['cargo'] != "ST")
            <div class="row">
                <div title="{{$data['nome']}}" class="h1 col-<?= $data['descricao'] ? '4 border-end border-dark' : '12' ?> pe-3 text-truncate">
                    Backlog de tarefas de {{$data['squad_nome']}}
                </div>
                <div>
                    <a href="/create-card">
                        <button class="col-3 btn btn-dark mt-3 mb-3">Adicionar tarefa ao backlog</button>
                    </a>
                    <a href="{{$teamDataAndPermission['squad_id']}}/create-sprint">
                        <button class="col-3 btn btn-dark mt-3 mb-3">Criar nova sprint</button>
                    </a>
                    <a href="{{$teamDataAndPermission['squad_id']}}/list-sprints">
                        <button class="col-3 btn btn-dark mt-3 mb-3">Visualizar sprints</button>
                    </a>
                </div>
            </div>
            <hr class="opacity-100">
            <div class="row mb-3 rounded p-1" style="background-color:#f2f2f2">
            <div class="col-12">
                <div class="row pe-4">
                    @foreach($columns as $column)
                    <livewire:src.kanban.coluna-cabecalho :columnData="(array) $column" />
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <div class="row vh-100 pe-4" style="overflow: overlay;">
                    @foreach($columns as $column)
                    <livewire:src.kanban.coluna-conteudo :alias="$alias" :columnData="(array) $column" :allColumns="(array) $columns" />
                    @endforeach
                </div>
            </div>
            </div>
        @else
        <div class="alert alert-warning mt-5" role="alert">
            Você não possui cargos para gerenciar o backlog.</br></br>
            <a href="/kanban">Retornar</a>
        </div>
        @endif
    @else
        <div class="alert alert-warning mt-5" role="alert">
            Não encontramos esta equipe.</br></br>
            <a href="/kanban">Retornar</a>
        </div>
    @endif
</div>
