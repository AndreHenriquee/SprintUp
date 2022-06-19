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
    <div class="row mb-3 rounded p-1" style="background-color:#f2f2f2">
        <div class="col-12">
            <div class="row pe-4">
                @foreach ($columns as $column)
                <livewire:src.kanban.coluna-cabecalho :columnData="(array) $column" />
                @endforeach
            </div>
        </div>
        <div class="col-12">
            <div class="row vh-100 pe-4" style="overflow: overlay;">
                @foreach ($columns as $column)
                <livewire:src.kanban.coluna-conteudo :columnData="(array) $column" />
                @endforeach
            </div>
        </div>
    </div>
</div>