<div class="collapse {{$tipo == 'DOING' ? 'show' : ''}} p-3" id="collapseFeatureList-{{$tipo}}">
    @if (!empty($features))
    <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
        <thead>
            <tr>
                <th scope="col">Funcionalidade</th>
                <th scope="col">Data de início</th>
                <th scope="col">Data estimada para o fim</th>
                <th scope="col">Porcentagem de conclusão</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($features as $feature)
            <tr style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalFeature-{{$feature->id}}">
                <th scope="row">{{$feature->nome}}</th>
                <td>{{date_format(date_create($feature->data_inicio),"d/m/Y")}}</td>
                <td>{{date_format(date_create($feature->data_fim),"d/m/Y")}}</td>
                <td>{{$feature->porcentagem_conclusao}}%</td>
            </tr>
            <livewire:src.roadmap.feature-modal :data="(array) $feature" :status="$typeMap[$tipo]['titulo']" />
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-secondary h5" role="alert">
        Nenhuma feature no status <b>{{$typeMap[$tipo]['titulo']}}</b> para mostrar.
    </div>
    @endif
</div>