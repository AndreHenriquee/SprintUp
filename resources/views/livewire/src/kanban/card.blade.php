<div class="card mb-3" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalCard-{{$data['id']}}">
    <div class="card-body">
        <h5 class="card-title text-wrap" style="text-overflow: ellipsis"> <b> {{$data['titulo']}} </b> </h5>
        <p class="card-text"> {{$data['referencia']}}
        @if($data['usuario_responsavel_nome'])
        <p> <b> Responsável: </b> {{$data['usuario_responsavel_nome']}}
        @else
        <p> <b> Responsável: </b> Nenhum
        @endif
        <p> <b> Relator: </b> {{$data['usuario_relator_nome']}}
        @if($data['estimativa'])
        <p> <b> Estimativa: </b> {{$data['estimativa']}}{{$data['extensao']}}
        @endif
    </div>
</div>