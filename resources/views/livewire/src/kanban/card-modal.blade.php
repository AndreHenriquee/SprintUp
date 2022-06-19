<div class="modal fade" id="modalCard-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$data['titulo']}} - {{$data['referencia']}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">Descrição</b>
                    <div class="col-sm-9 p-3">
                        <p class="text-wrap">{{$data['detalhamento']}}</p>
                    </div>
                    <div class="col-3 p-3 bg-light">
                        <p class="text-wrap">Responsavel: {{$data['usuario_responsavel_nome']}}</p>
                        <p class="text-wrap">Relator: {{$data['usuario_relator_nome']}}</p>
                        <p class="text-wrap">Prioridade: {{$data['prioridade']}}</p>
                        <p class="text-wrap">Status: {{$data['nome_coluna']}}</p>
                        <p class="text-wrap">Estimativa: {{$data['estimativa']}}{{$data['extensao']}}</p>
                        <p class="text-wrap">Criado em: {{$data['data_hora_criacao']}}</p>
                        <p class="text-wrap">Atualizado em: {{$data['data_hora_ultima_movimentacao']}}</p>
                    </div>
                </div>
                <!-- <div class="row">
                    <b class="modal-title">Comentarios</b>
                </div> -->
            </div>
        </div>
    </div>
</div>