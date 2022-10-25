<div class="container">
    @if(
    isset($teamDataAndPermission['nome'])
    )
    <div class="row">
        <div title="Produtos da equipe {{$teamDataAndPermission['nome']}}" class="h1 col-4 pe-3 border-end border-dark text-truncate">
            Produtos da equipe {{$teamDataAndPermission['nome']}}
        </div>
        <div title="Gerencie aqui os produtos da equipe {{$teamDataAndPermission['nome']}}" class="col-8 my-auto text-truncate">
            Gerencie aqui os produtos da equipe <b>{{$teamDataAndPermission['nome']}}</b>
        </div>
    </div>
    <hr class="opacity-100">
    @if($teamDataAndPermission['permissao_gerenciar_produtos'])
    <div class="row mb-3">
        <div class="col">
            <a href="/novo-produto/{{$routeParams['equipe_id']}}" class="btn btn-primary btn-dark">Criar novo produto nesta equipe</a>
        </div>
    </div>
    @endif
    <div class="row rounded p-3 mt-3 mb-2" style="background-color:#f2f2f2">
        <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Número de funcionalidades</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $numberOfProducts = count($teamProducts) ?>
                @foreach($teamProducts as $teamProduct)
                <tr>
                    <td style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalProduct-{{$teamProduct->id}}">
                        {{$teamProduct->nome}}
                    </td>
                    <td style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalProduct-{{$teamProduct->id}}">
                        {{$teamProduct->descricao}}
                    </td>
                    <td style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalProduct-{{$teamProduct->id}}">
                        {{$teamProduct->numero_funcionalidades}}
                    </td>
                    <td>
                        @if($teamDataAndPermission['permissao_gerenciar_produtos'])
                        <a title="Excluir produto" class="text-dark me-3" style="cursor:pointer; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modalExcludeProduct-{{$teamProduct->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </a>
                        <livewire:src.roadmap-products.sure-to-exclude-product-modal :productData="(array) $teamProduct" :numberOfProducts="$numberOfProducts" />
                        @endif
                        @if($teamDataAndPermission['permissao_gerenciar_funcionalidades'])
                        <a title="Adicionar funcionalidade para este produto" class="text-dark" style="cursor:pointer; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modalAddItemToProduct-{{$teamProduct->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-node-plus" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6.025 7.5a5 5 0 1 1 0 1H4A1.5 1.5 0 0 1 2.5 10h-1A1.5 1.5 0 0 1 0 8.5v-1A1.5 1.5 0 0 1 1.5 6h1A1.5 1.5 0 0 1 4 7.5h2.025zM11 5a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 11 5zM1.5 7a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                            </svg>
                        </a>
                        <livewire:src.roadmap-products.add-item-to-product-modal :productData="(array) $teamProduct" :allowedToChangeStatus="(int) $teamDataAndPermission['permissao_gerenciar_status_funcionalidades']" />
                        @endif
                    </td>
                </tr>
                <livewire:src.roadmap-products.product-modal :data="(array) $teamProduct" :teamId="$teamDataAndPermission['id']" />
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        Verifique se a equipe passada como parâmetro realmente existe.
    </div>
    @endif
</div>