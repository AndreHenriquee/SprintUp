<div class="modal fade" id="modalFeature-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{$data['nome']}} | {{$data['produto_nome']}}
                </h5>
                <div class="modal-title col pe-4 text-end">
                    {{date_format(date_create($data['data_inicio']),"d/m/Y")}} - {{date_format(date_create($data['data_fim']),"d/m/Y")}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">
                        {{$status}} | Porcentagem de conclusão: {{$data['porcentagem_conclusao']}}% ({{$data['finalizada'] ? 'Finalizada' : 'Não finalizada'}})
                    </b>
                    <div class="col-12 p-3">
                        <p class="text-wrap">{{$data['descricao']}}</p>
                    </div>
                </div>
            </div>
            @if ($data['data_hora_replanejamento'])
            <div class="modal-footer text-end">
                Último replanejamento: {{date_format(date_create($data['data_hora_replanejamento']),"d/m/Y H:i:s")}}
            </div>
            @endif
            <!-- <div class="row">
                <b class="modal-title">Comentários</b>
            </div> -->
        </div>
    </div>
</div>