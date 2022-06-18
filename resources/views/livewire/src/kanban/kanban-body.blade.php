<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div title="{{$data['nome']}}" class="h1 col-4 pe-3 text-truncate border-end border-dark">
                    {{$data['nome']}}
                </div>
                <div title="{{$data['descricao']}}" class="col-8 my-auto text-truncate">
                    {{$data['descricao']}}
                </div>
            </div>
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row mb-3 bg-dark rounded p-1">
        <div class="col-12">
            <div class="row pe-4">
                @foreach ($columns as $column)
                <livewire:src.kanban.coluna-cabecalho :nome="$column->nome" :descricao="$column->descricao" :inicio_tarefa="$column->inicio_tarefa" :fim_tarefa="$column->fim_tarefa" :wip="$column->wip" />
                @endforeach
            </div>
        </div>
        <div class="col-12">
            <div class="row vh-100 pe-4" style="overflow: overlay;">
                @foreach ($columns as $column)
                <div id="column-{{$column->coluna_id}}" class="col p-2 bg-secondary rounded-bottom ms-1 me-1 mb-1">
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                    <livewire:src.kanban.card />
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>