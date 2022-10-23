<div class="col p-2 rounded-bottom ms-1 me-1 mb-1 {{$wipHitted ? 'border border-top-0 border-danger' : ''}}" style="background-color:#e6e6e6;">
    <?php $location = request()->route()->uri ?>

    @foreach($cards as $card)
        <livewire:src.kanban.card :data="(array) $card" />
        <livewire:src.kanban.card-modal :alias="$alias" :data="(array) $card" :columns="(array) $allColumns"/>
    @endforeach


    @if ($wipHitted)
    <script>
        columnHeader = document.getElementById("columnHead-{{$columnData['id']}}");
        columnHeader.classList.add("border");
        columnHeader.classList.add("border-bottom-0");
        columnHeader.classList.add("border-danger");

        wipLabel = document.getElementById("wipLabel-{{$columnData['id']}}");
        columnHeader.classList.add("text-danger");
    </script>
    @endif
</div>