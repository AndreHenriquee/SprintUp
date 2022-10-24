<div class="container {{!isset($routeParams['equipe_id']) ? '' : 'pt-4'}}">
    @if (empty($features['TO_DO']) && empty($features['DOING']) && empty($features['DONE']))
    @if (
    !empty($teamDataAndPermission) &&
    ($teamDataAndPermission['permissao_gerenciar_produtos']
    && in_array($teamDataAndPermission['cargo'], ['PO', 'SM']))
    )
    <div class="row">
        <div class="col">
            <a href="/roadmap-produtos/{{$teamDataAndPermission['id']}}" class="btn btn-dark" style="cursor:pointer; text-decoration: none;">
                Gerenciar produtos e funcionalidades
            </a>
        </div>
    </div>
    @endif
    <div class="alert alert-warning mt-4" role="alert">
        Esta equipe não existe ou não tem nenhuma funcionalidade para mostrar
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.emit('validateRouteParams');
        });
    </script>
    @else
    <div class="row">
        <div title="Roadmap | {{$teamData['nome']}}" class="h1 col-5 pe-3 text-truncate border-end border-dark">
            Roadmap | {{$teamData['nome']}}
        </div>
        <div class="col-7 my-auto">
            Aqui está o histórico e o planejamento das funcionalidades trabalhadas pela equipe <b>{{$teamData['nome']}}</b>
        </div>
        @if (
        !empty($teamDataAndPermission)
        && ($teamDataAndPermission['permissao_gerenciar_produtos']
        && in_array($teamDataAndPermission['cargo'], ['PO', 'SM']))
        )
        <hr class="opacity-100">
        <div class="row mb-3">
            <div class="col">
                <a href="/roadmap-produtos/{{$teamDataAndPermission['id']}}" class="btn btn-dark" style="cursor:pointer; text-decoration: none;">
                    Gerenciar produtos e funcionalidades
                </a>
            </div>
        </div>
        @endif
    </div>
    <hr class="opacity-100">
    <div class="row mb-3">
        <div class="col-auto">
            <select wire:model="selectedProductId" class="form-select" id="productSelect">
                <option value="0">Filtrar por produto</option>
                @foreach($products as $product)
                <option value="{{$product->id}}">
                    {{$product->nome}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button wire:click="updateFilter" type="submit" class="btn btn-primary btn-dark">Buscar</button>
        </div>

        <script>
            document.addEventListener('livewire:load', function() {
                var selectedProduct = "{{$routeParams['produto_id']}}";
                document.getElementById('productSelect').value = selectedProduct == "" ? 0 : selectedProduct;
                document.getElementById('productSelect').dispatchEvent(new Event('change'));

                Livewire.emit('validateRouteParams');
            });
        </script>
    </div>
    <div class="row mb-3 rounded p-3" style="background-color:#f2f2f2">
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'TO_DO'" />
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'DOING'" />
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'DONE'" />
    </div>
    @endif
</div>