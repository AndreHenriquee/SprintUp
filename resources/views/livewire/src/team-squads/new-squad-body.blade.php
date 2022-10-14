<div class="container">
    @if(
    isset($teamDataAndPermission['nome'])
    )
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Crie uma nova Squad para a equipe <b>{{$teamDataAndPermission['nome']}}</b></h2>
        </div>
        <div class="modal-body">
            <div class="row mt-3">
                <h4>Informações da squad</h4>
                <div class="col-12 mt-2">
                    <input type="text" wire:model="nomeSquad" class="form-control" placeholder="Nome da Squad" />
                    @error('nomeSquad') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <input type="text" wire:model="descSquad" class="form-control" placeholder="Descrição breve da Squad" />
                    @error('descSquad') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" wire:click="resetFields" class="btn btn-secondary" value="Limpar campos" />
            <button wire:click="registerSquad" class="btn btn-dark">Criar Squad</button>
            <p class="mt-3">Certifique-se de que os campos foram preenchidos corretamente.</p>
        </div>
    </div>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        Verifique se a equipe passada como parâmetro realmente existe.
    </div>
    @endif
</div>