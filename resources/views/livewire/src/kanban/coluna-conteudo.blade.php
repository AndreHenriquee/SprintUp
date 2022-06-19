<div class="col p-2 rounded-bottom ms-1 me-1 mb-1 {{$wipHitted ? 'border border-top-0 border-danger' : ''}}" style="background-color:#e6e6e6;">
    @foreach ($cards as $card)
    <livewire:src.kanban.card :data="(array) $card" />
    @endforeach
</div>

@if ($wipHitted)
<script>
    columnHeader = document.getElementById("columnHead-{{$columnData['coluna_id']}}");
    columnHeader.classList.add("border");
    columnHeader.classList.add("border-bottom-0");
    columnHeader.classList.add("border-danger");

    wipLabel = document.getElementById("wipLabel-{{$columnData['coluna_id']}}");
    columnHeader.classList.add("text-danger");
</script>
@endif