<div class="collapse <?= $hasFilters && isset($documentacoes[$tipo]) ? 'show' : '' ?> p-3" id="collapseDocList-{{$tipo}}">
    @if (isset($documentacoes[$tipo]))
    <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Título</th>
                <th scope="col">Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documentacoes[$tipo] as $documentacao)
            <tr style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalDocument-{{$documentacao->id}}">
                <th scope="row">{{$documentacao->referencia}}</th>
                <td>{{$documentacao->titulo}}</td>
                <td>{{date_format(date_create($documentacao->data_hora),"d/m/Y H:i:s")}}</td>
            </tr>
            <livewire:src.documentacoes.document-modal :teamDataAndPermission="$teamDataAndPermission" :data="(array) $documentacao" :typeMap="$typeMap" />
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-secondary h5" role="alert">
        Nenhuma documentação de {{$typeMap[$tipo]['titulo']}} foi cadastrada para a sua equipe.
    </div>
    @endif
</div>