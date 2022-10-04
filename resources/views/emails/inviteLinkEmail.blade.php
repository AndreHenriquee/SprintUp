<html>

<body>
    <p>Olá! Aqui é do grupo Sprint Up!</p>
    <br />
    <p>Estamos passando aqui para avisar que você foi convidado para entrar na squad <b>{{$emailInfo['squad_nome']}}</b> da equipe <b>{{$emailInfo['equipe_nome']}}!</b></p>
    <br />
    <p>Você assumirá, dentro desta squad, o papel de <b>{{$emailInfo['cargo_nome']}}</b> e as permissões de <b>{{$emailInfo['grupo_permissao_nome']}}</b>.</p>
    <br />
    <a href="http://localhost:8000/aceitar-link-convite/{{$emailInfo['hash']}}">Clique aqui para aceitar o convite.</a>
</body>

</html>