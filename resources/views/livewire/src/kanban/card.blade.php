<div class="card mb-3" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalCard-{{$data['id']}}">
    <div class="card-body">
        <h5 class="card-title text-wrap" style="text-overflow: ellipsis">{{$data['titulo']}}</h5>
        <p class="card-text">{{$data['referencia']}}
        @if($data['usuario_responsavel_nome'])
        <p>Responsável: {{$data['usuario_responsavel_nome']}}
        @else
        <p>Responsável: Nenhum
        @endif
        <p>Status {{$data['nome_coluna']}}
        <p>Relator: {{$data['usuario_relator_nome']}}
        @if($data['estimativa'])
        <p>Estimativa: {{$data['estimativa']}}{{$data['extensao']}}
        @endif
    </div>
</div>