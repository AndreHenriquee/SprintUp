<div wire:ignore.self class="modal fade" id="modalProduct-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="row">
                    <div class="col-11">
                        <input wire:model="productName" class="modal-title h5 m-0 w-100 border-0 border-bottom" id="productName-{{$data['id']}}" type="text" autocomplete="off" placeholder="Nome">
                        @error('productName') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class='col-1 text-end'>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 border-end overflow-auto" style="max-height: 400px;">
                        <textarea wire:model="productDescription" id="productDescription-{{$data['id']}}" class="form-control bg-white" rows="12" autocomplete="off" placeholder="Descrição do produto"></textarea>
                        @error('productDescription') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($data['numero_funcionalidades'] > 0)
                <a href="/roadmap/{{$data['id']}}" class="btn btn-secondary">Ver funcionalidades desse produto</a>
                @else
                <div class="alert alert-info" role="alert">
                    Este produto não possui nenhuma funcionalidade ligada a ele
                </div>
                @endif
                <button wire:click="saveChanges" class="btn btn-primary">Salvar alterações</button>
                <script>
                    document.addEventListener('livewire:load', function() {
                        var inputedProductName = "{{$data['nome']}}";
                        var inputedProductDescription = `{{$data['descricao']}}`;

                        var productName = document.getElementById("productName-{{$data['id']}}");
                        productName.value = inputedProductName;
                        productName.dispatchEvent(new Event('input'));

                        var productDescription = document.getElementById("productDescription-{{$data['id']}}");
                        productDescription.value = inputedProductDescription;
                        productDescription.dispatchEvent(new Event('input'));

                        Livewire.on("noDataChanged-{{$data['id']}}", () => {
                            alert('Nada foi alterado neste produto!');
                        });
                    });
                </script>
            </div>
        </div>
    </div>