<div class="container">
    <div class="row">
        <div title="{{$data['nome']}}" class="h1 col-<?= $data['descricao'] ? '4 border-end border-dark' : '12' ?> pe-3 text-truncate">
            {{$data['nome']}}
        </div>
        @if($data['descricao'])
        <div title="{{$data['descricao']}}" class="col-8 my-auto text-truncate">
            {{$data['descricao']}}
        </div>
        @endif
        <div>
            <a href="/kanban/create-card">
                <button class="col-2 btn btn-dark mt-3 mb-3">Criar Card</button>
            </a>
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