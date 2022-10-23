<div wire:ignore.self class="modal fade" id="modalExcludeProduct-{{$productData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você o produto <b>{{$productData['nome']}}</b>.</p>
                <p><b>Todas as funcionalidades dele ({{$numberOfProducts}} no total) deixarão de aparecer no Roadmap da equipe</b>.</p>
                @if($numberOfProducts == 1)
                <p>Este produto também é o <b>único</b> existente nesta equipe, o que significa que excluí-lo fará a equipe <b>não aparecer no Roadmap para o cliente</b>.</p>
                @endif
                <p>Tem certeza de que deseja prosseguir? <b>Esta ação é irreversível.</b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="excludeProduct" type="button" class="btn btn-primary">Sim, excluir este produto</button>
            </div>
        </div>
    </div>
</div>