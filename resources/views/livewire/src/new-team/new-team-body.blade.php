<div class="container">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Crie uma nova equipe</h2>
        </div>
        <div class="modal-body">
            <div class="row">
                <h4>Informações da equipe</h4>
                <div class="col-12 mt-2">
                    <input type="text" wire:model="nomeEquipe" class="form-control" placeholder="Nome da Equipe" />
                    @error('nomeEquipe') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <input type="text" wire:model="descEquipe" class="form-control" placeholder="Descrição breve da equipe" />
                    @error('descEquipe') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <div class="form-check">
                        Deseja criar um Roadmap para sua equipe?
                        <input type="checkbox" wire:model="roadmapCheckbox" class="form-check-input">
                    </div>
                </div>
            </div>
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
            <button wire:click="registerTeam" class="btn btn-dark">Criar equipe</button>
            <input type="button" wire:click="resetFields" class="btn btn-secondary" value="Limpar campos" />
            <p class="mt-3">Certifique-se de que os campos foram preenchidos corretamente.</p>
        </div>
    </div>
</div>