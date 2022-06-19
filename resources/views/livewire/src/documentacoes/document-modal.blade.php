<div class="modal fade" id="modalDocument-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{$data['referencia']}} | {{$data['titulo']}}
                </h5>
                <div class="modal-title col pe-4 text-end">
                    {{date_format(date_create($data['data_hora']),"d/m/Y H:i:s")}}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">{{$typeMap[$data['tipo']]['titulo']}}</b>
                    <div class="col-12 p-3">
                        <p class="text-wrap">{{$data['conteudo']}}</p>
                    </div>
                </div>
                <!-- <div class="row">
                    <b class="modal-title">Comentários</b>
                </div> -->
            </div>
        </div>
    </div>
</div>