<div wire:ignore.self class="modal fade" id="modalExcludeSquads-{{$squadData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Squad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($numberOfSquads > 1)
                <p>Você está excluindo a squad <b>{{$squadData['referencia']}} | {{$squadData['nome']}}</b>.</p>
                <p><b>Todos</b> os membros dela <b>perderão o acesso</b> a ela, <b>incluindo você</b>, se for o caso.</p>
                <p>Tem certeza de que deseja prosseguir? <b>Esta ação é irreversível.</b></p>
                @else
                <p>Você não pode excluir esta Squad pois ela é a única existente nesta equipe.</p>
                @endif
            </div>
            <div class="modal-footer">
                @if($numberOfSquads > 1)
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="excludeSquad" type="button" class="btn btn-primary">Sim, excluir esta Squad</button>
                @else
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendi</button>
                @endif
            </div>
        </div>
    </div>
</div>