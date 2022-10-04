<div class="container pt-4">
    @if($isHashValid)
    <div class="alert alert-primary mt-5" role="alert">
        Estamos processando o seu convite.
        <br />
        Aguarde um momento.
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('alertWrongEmail', () => {
                alert('Este link de convite não é válido para esse e-mail.');
                window.location.href = '/kanban';
            })

            Livewire.emit('processInviteAcception');
        });
    </script>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        Este link de convite não existe ou já foi usado.
        <br />
        Confirme se o link está certo ou peça para que lhe enviem um novo convite.
        <br />
        Se você está apenas tentando se logar, <a href="/kanban">clique aqui</a>.
    </div>
    @endif
</div>