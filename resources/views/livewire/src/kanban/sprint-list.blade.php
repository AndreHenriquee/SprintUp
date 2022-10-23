<div class="collapse" id="collapseSprintList">
    @if (isset($sprintDetails))
    <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Inicio</th>
                <th scope="col">Fim</th>
                <th scope="col">Tarefas Relacionadas</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sprintDetails as $details)
            <tr style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalDocument-{{$details->id}}">
                <th scope="row">{{$details->numero}}</th>
                <td>{{date_format(date_create($details->inicio),"d/m/Y")}}</td>
                <td>{{date_format(date_create($details->fim),"d/m/Y")}}</td>
                <td>{{$details->referencias}}</td>
                <td> @if ($details->finalizada == 0) Em andamento @endif</td>  
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-secondary h5" role="alert">
        Nenhuma sprint foi criada para a sua squad.
    </div>
    @endif
</div>