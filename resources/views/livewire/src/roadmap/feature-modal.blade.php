<div wire:ignore.self class="modal fade" id="modalFeature-{{$data['id']}}" tabindex="-1">
    <?php
    $rolePermission = in_array($teamDataAndPermission['cargo'], ['PO', 'SM']);

    $allowedToManageItem = $rolePermission && $teamDataAndPermission['permissao_gerenciar_funcionalidades'];
    $allowedToManageItemStatus = $rolePermission && $teamDataAndPermission['permissao_gerenciar_status_funcionalidades'];
    $allowedToAnswerCustomers = $teamDataAndPermission['permissao_responder_clientes'];
    ?>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="row">
                    <div class="col-6">
                        <input <?= $allowedToManageItem ? '' : 'disabled' ?> wire:model="featureName" class="modal-title h5 m-0 w-100 border-0 border-bottom" id="featureName-{{$data['id']}}" type="text" autocomplete="off" placeholder="Nome">
                        @error('featureName') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-2">
                        <input <?= $allowedToManageItem ? '' : 'disabled' ?> class="form-control" id="initialDateInput-{{$data['id']}}" type="date">
                        <input <?= $allowedToManageItem ? '' : 'disabled' ?> class="d-none" type="text" wire:model="initialDate" id="initialDateInput-{{$data['id']}}-hidden">
                        @error('initialDate') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-1 my-auto h5 text-center">
                        -
                    </div>
                    <div class="col-2">
                        <input <?= $allowedToManageItem ? '' : 'disabled' ?> class="form-control" id="endDateInput-{{$data['id']}}" type="date">
                        <input <?= $allowedToManageItem ? '' : 'disabled' ?> class="d-none" type="text" wire:model="endDate" id="endDateInput-{{$data['id']}}-hidden">
                        @error('endDate') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class='col-1 text-end'>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5">
                        <input <?= $allowedToManageItem && $allowedToManageItemStatus ? '' : 'disabled' ?> wire:model="conclusionPercentage" type="range" min="0" max="100" step="1" class="form-range">
                    </div>
                    <div class="col-2">
                        <p class="h6">{{$conclusionPercentage}}% concluído</p>
                    </div>
                    <div class="col-5">
                        <div class="form-check h6">
                            O trabalho nesta funcionalidade já foi encerrado??
                            <input <?= $allowedToManageItem && $allowedToManageItemStatus ? '' : 'disabled' ?> type="checkbox" wire:model="isFinalized" class="form-check-input">
                        </div>
                    </div>
                    <div class="col-12">
                        <textarea <?= $allowedToManageItem ? '' : 'disabled' ?> wire:model="featureDescription" rows="12" class="form-control bg-white" autocomplete="off" placeholder="Descrição da funcionalidade"></textarea>
                        @error('featureDescription') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    @if ($data['data_hora_replanejamento'])
                    <div class="col-12 text-end">
                        Último replanejamento: {{date_format(date_create($data['data_hora_replanejamento']),"d/m/Y H:i:s")}}
                    </div>
                    @endif
                </div>
                <div class="modal-footer mt-3">
                    <button <?= $allowedToManageItem ? '' : 'disabled' ?> id="excludeItem-{{$data['id']}}" class="btn btn-danger">Excluir funcionalidade</button>
                    <button <?= $allowedToManageItem ? '' : 'disabled' ?> wire:click="saveChanges" type="button" class="btn btn-primary">Salvar alterações</button>
                </div>
            </div>
            <!-- <div class="row">
                <b class="modal-title">Comentários</b>
            </div> -->
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

        document.addEventListener('livewire:load', function() {
            var initialDate = "{{$data['data_inicio']}}";
            var endDate = "{{$data['data_fim']}}";

            document.getElementById("initialDateInput-{{$data['id']}}").value = initialDate;
            var initialDateInput = document.getElementById("initialDateInput-{{$data['id']}}-hidden");
            initialDateInput.value = initialDate;
            initialDateInput.dispatchEvent(new Event('input'));

            document.getElementById("endDateInput-{{$data['id']}}").value = endDate;
            var endDateInput = document.getElementById("endDateInput-{{$data['id']}}-hidden");
            endDateInput.value = endDate;
            endDateInput.dispatchEvent(new Event('input'));

            document.getElementById("excludeItem-{{$data['id']}}").addEventListener('click', function() {
                if (confirm('Você realmente quer excluir esta funcionalidade?\n\nEsta ação é irreversível! Pense bem antes de confirmar.') == true) {
                    Livewire.emit("excludeItem-{{$data['id']}}");
                }
            });
        });
    </script>
</div>