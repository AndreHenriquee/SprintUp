<div class="card mb-3" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalCard-{{$data['id']}}">
    <div class=" card-body">
        <h5 class="card-title text-wrap" style="text-overflow: ellipsis">{{$data['titulo']}}</h5>
        <p class="card-text">{{$data['referencia']}}
        <p>Responsável: {{$data['usuario_responsavel_nome']}}
        <p>Relator: {{$data['usuario_relator_nome']}}
        <p>Estimativa: {{$data['estimativa']}}{{$data['extensao']}}
    </div>
</div>
<livewire:src.kanban.card-modal :data="$data" />
<script>
    var modalCard = document.getElementById("modalCard-{{$data['id']}}")

    addEventListener('showModal', () => {
        modalCard.focus()
    })
</script>