<div class="container">
    @if(
    isset($teamDataAndPermission['nome'])
    )
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Crie uma nova documentação para a equipe <b>{{$teamDataAndPermission['nome']}}</b></h2>
        </div>
        <div class="modal-body">
            <div class="row mt-2">
                <div class="col-7">
                    <input type="text" wire:model="docTitle" class="form-control" placeholder="Título da documentação" />
                    @error('docTitle') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-3">
                    <select wire:model="docType" class="form-select">
                        <option value="">Tipo da documentação</option>
                        @foreach($docTypes as $dtRef => $dtName)
                        <?php
                        $permitedType = $dtRef == 'INFORMATION'
                            || in_array($teamDataAndPermission['cargo'], ['PO', 'SM']);
                        ?>
                        <option <?= $permitedType ? '' : 'disabled' ?> value="{{$dtRef}}">{{$dtName}}</option>
                        @endforeach
                    </select>
                    @error('docType') <span class="text-danger error">O tipo é obrigatótio</span> @enderror
                </div>
                <div class="col-2">
                    <input class="form-control" id="docDateInput" type="date">
                    <input class="d-none" type="text" wire:model="docDate" id="docDateInput-hidden">
                    @error('docDate') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <textarea wire:model="docContent" class="form-control" rows="10" placeholder="Conteúdo da documentação"></textarea>
                    @error('docContent') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <div class="form-check">
                        <input wire:model="isLimitedToSquad" class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            Limitar acesso a esta documentação apenas à Squad atual
                        </label>
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
        <div class="modal-footer">
            <button wire:click="registerDoc" class="btn btn-dark">Registrar documentação</button>
            <p class="mt-3">Certifique-se de que os campos foram preenchidos corretamente.</p>
        </div>
    </div>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        Verifique se a equipe passada como parâmetro realmente existe.
    </div>
    @endif
</div>