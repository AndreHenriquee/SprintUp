<div class="col p-2 rounded-top ms-1 mt-1 me-1" style="background-color:#e6e6e6;">
    <div class="row">
        <div title="{{$nome}} | {{$descricao}}" class="col-7 h5 text-truncate text-dark">
            @if ($inicio_tarefa)
            <svg xmlns="http://www.w3.org/2000/svg" width="1.125rem" height="1.125rem" fill="currentColor" class="bi bi-compass" viewBox="0 0 16 16">
                <path d="M8 16.016a7.5 7.5 0 0 0 1.962-14.74A1 1 0 0 0 9 0H7a1 1 0 0 0-.962 1.276A7.5 7.5 0 0 0 8 16.016zm6.5-7.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                <path d="m6.94 7.44 4.95-2.83-2.83 4.95-4.949 2.83 2.828-4.95z" />
            </svg>
            @endif

            @if ($fim_tarefa)
            <svg xmlns="http://www.w3.org/2000/svg" width="1.125rem" height="1.125rem" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z" />
                <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
            </svg>
            @endif

            {{$nome}}
        </div>

        <div class="col-5 h6 text-end">
            @if ($wip)
            WIP: {{$wip}}
            @endif
        </div>
    </div>
</div>