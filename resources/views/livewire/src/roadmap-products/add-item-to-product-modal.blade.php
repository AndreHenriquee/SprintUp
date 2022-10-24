<div wire:ignore.self class="modal fade" id="modalAddItemToProduct-{{$productData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar funcionalidade ao produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você está adicionando uma funcionalidade ao produto <b>{{$productData['nome']}}</b></p>
                <div class="row">
                    <div class="col-12 mb-3">
                        <input wire:model="itemName" class="form-control" type="search" autocomplete="off" placeholder="Nome da funcionalidade">
                        @error('itemName') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <textarea wire:model="itemDescription" class="form-control bg-white" autocomplete="off" placeholder="Descrição da funcionalidade"></textarea>
                        @error('itemDescription') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <p class="h6">Data estimada para o início do desenvolvimento</p>
                        <input class="form-control" id="initialDateInput-{{$productData['id']}}" type="date">
                        <input class="d-none" type="text" wire:model="initialDate" id="initialDateInput-{{$productData['id']}}-hidden">
                        @error('initialDate') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <p class="h6">Data prevista para o release</p>
                        <input class="form-control" id="endDateInput-{{$productData['id']}}" type="date">
                        <input class="d-none" type="text" wire:model="endDate" id="endDateInput-{{$productData['id']}}-hidden">
                        @error('endDate') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    @if($allowedToChangeStatus)
                    <div class="col-12 mb-3">
                        <p class="h6">Porcentagem de conclusão da funcionalidade até agora</p>
                        <input wire:model="conclusionPercentage" type="range" min="0" max="100" step="1" class="form-range">
                        <p class="h6">{{$conclusionPercentage}}% concluído</p>
                    </div>
                    <div class="col-12">
                        <div class="form-check h6">
                            O trabalho nesta funcionalidade já foi encerrado??
                            <input type="checkbox" wire:model="isFinalized" class="form-check-input">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="createItem" type="button" class="btn btn-primary">Criar funcionalidade</button>
            </div>
        </div>
    </div>
    <script>
        [...document.querySelectorAll('input[type="date"]')].forEach(dateInput => {
            dateInput.addEventListener('input', function(e) {
                var input = e.target,
                    hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');

                hiddenInput.value = input.value;

                hiddenInput.dispatchEvent(new Event('input'));
            })
        });
    </script>
</div>