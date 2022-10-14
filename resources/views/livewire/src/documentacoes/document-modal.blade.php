<div wire:ignore.self class="modal fade" id="modalDocument-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="row">
                    <div title="{{$data['referencia']}}" class="col-1 h5 modal-title text-truncate">
                        {{$data['referencia']}}
                    </div>
                    <div class="col-7">
                        <input wire:model="docTitle" class="modal-title h5 m-0 w-100 border-0 border-bottom" id="docTitle-{{$data['id']}}" type="text" autocomplete="off" placeholder="Título">
                        @error('docTitle') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-3 text-end">
                        {{date_format(date_create($data['data_hora']),"d/m/Y H:i:s")}}
                    </div>
                    <div class='col-1 text-end'>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">{{$typeMap[$data['tipo']]['titulo']}}</b>
                    <div class="col-12 p-3">
                        <textarea wire:model="docContent" id="docContent-{{$data['id']}}" class="form-control bg-white" rows="10" autocomplete="off" placeholder="Conteúdo da documentação"></textarea>
                        @error('docContent') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <!-- <div class="row">
                    <b class="modal-title">Comentários</b>
                </div> -->
            </div>
            <div class="modal-footer">
                <button wire:click="saveChanges" class="btn btn-dark">Salvar alterações</button>
            </div>
            <script>
                document.addEventListener('livewire:load', function() {
                    var inputedDocTitle = "{{$data['titulo']}}";
                    var inputedDocContent = `{{$data['conteudo']}}`;

                    var docTitle = document.getElementById("docTitle-{{$data['id']}}");
                    docTitle.value = inputedDocTitle;
                    docTitle.dispatchEvent(new Event('input'));

                    var docContent = document.getElementById("docContent-{{$data['id']}}");
                    docContent.value = inputedDocContent;
                    docContent.dispatchEvent(new Event('input'));

                    Livewire.on("noDataChanged-{{$data['id']}}", () => {
                        alert('Nada foi alterado nesta documentação!');
                    })
                });
            </script>
        </div>
    </div>
</div>