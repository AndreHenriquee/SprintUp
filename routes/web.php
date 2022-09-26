<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('MAIN_VIEW', 'main');

function buildView(
    string $pageAlias,
    string $pageTitle,
    string $bodyComponentName,
    bool $loadMenu = true,
    array $routeParams = []
) {
    return view(
        MAIN_VIEW,
        [
            'alias' => $pageAlias,
            'title' => $pageTitle,
            'body' => $bodyComponentName,
            'loadMenu' => $loadMenu,
            'routeParams' => $routeParams,
        ]
    );
}

Route::get('/', function () {
    return buildView(
        'login',
        'Sprint Up | Login',
        'src.login.login-body',
        false
    );
});

Route::get('register', function () {
    return buildView(
        'register',
        'Sprint Up | Cadastro',
        'src.register.register-form',
        false
    );
});

Route::get('recovery', function () {
    return buildView(
        'recovery',
        'Sprint Up | Recuperação de senha',
        'src.login.account-recovery',
        false
    );
});


Route::get('kanban', function () {
    return buildView(
        'kanban',
        'Sprint Up | Kanban',
        'src.kanban.kanban-body'
    );
});

Route::get('kanban/create-card', function () {
    return buildView(
        'kanban',
        'Sprint Up | Kanban',
        'src.kanban.create-card'
    );
});

Route::get('documentacoes/{texto?}/{mencao_tarefa?}/{mencao_membro?}/{data?}', function (
    $texto = null,
    $mencao_tarefa = null,
    $mencao_membro = null,
    $data = null
) {
    return buildView(
        'documentacoes',
        'Sprint Up | Documentações',
        'src.documentacoes.documentacoes-body',
        true,
        [
            'texto' => $texto,
            'mencao_tarefa' => $mencao_tarefa,
            'mencao_membro' => $mencao_membro,
            'data' => $data,
        ]
    );
});

Route::get('roadmap/{produto_id?}', function ($produto_id = null) {
    return buildView(
        'roadmap',
        'Sprint Up | Roadmap',
        'src.roadmap.roadmap-body',
        true,
        ['produto_id' => $produto_id]
    );
});

Route::get('roadmap-cliente/{equipe_id}/{produto_id?}', function ($equipe_id,  $produto_id = null) {
    return buildView(
        'roadmap-cliente',
        'Sprint Up | Roadmap - Visão do cliente',
        'src.roadmap.roadmap-body',
        false,
        ['equipe_id' => $equipe_id, 'produto_id' => $produto_id]
    );
});

Route::get('equipes', function () {
    return buildView(
        'equipes',
        'Sprint Up | Equipes',
        'src.team.team-body'
    );
});

Route::get('convite-equipe/{equipe_id}', function ($equipe_id) {
    return buildView(
        'convite-equipe',
        'Sprint Up | Convite para a equipe',
        'src.team-invite.team-invite-body',
        true,
        ['equipe_id' => $equipe_id]
    );
});

Route::get('nova-equipe', function () {
    return buildView(
        'nova-equipe',
        'Sprint Up | Nova equipe',
        'src.new-team.new-team-body'
    );
});
