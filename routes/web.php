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
        'decide-home',
        'Sprint Up | Carregando visualização personalizada',
        'page.decide-home',
        false
    );
});

Route::get('login/{hash_convite?}', function (string $hash_convite = '') {
    return buildView(
        'login',
        'Sprint Up | Login',
        'src.login.login-body',
        false,
        ['hash_convite' => $hash_convite]
    );
});

Route::get('login-cliente', function () {
    return buildView(
        'login-cliente',
        'Sprint Up | Login do cliente',
        'src.customer-login.customer-login-body',
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

Route::get('register-cliente', function () {
    return buildView(
        'register-cliente',
        'Sprint Up | Cadastro do cliente',
        'src.customer-register.customer-register-body',
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
        'kanban-create-card',
        'Sprint Up | Criar card',
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

Route::get('nova-documentacao/{equipe_id}', function (int $equipe_id) {
    return buildView(
        'nova-documentacao',
        'Sprint Up | Registrar nova documentação',
        'src.documentacoes.new-document',
        true,
        ['equipe_id' => $equipe_id]
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

Route::get('roadmap-cliente/', function () {
    return buildView(
        'roadmap-cliente-escolha-time',
        'Sprint Up | Roadmap - Visão do cliente - Escolha da equipe',
        'src.roadmap-team-choice.roadmap-team-choice-body',
        false,
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

Route::get('roadmap-produtos/{equipe_id}/', function ($equipe_id) {
    return buildView(
        'roadmap-produtos',
        'Sprint Up | Produtos do Roadmap',
        'src.roadmap-products.roadmap-products-body',
        true,
        ['equipe_id' => $equipe_id]
    );
});

Route::get('novo-produto/{equipe_id}/', function ($equipe_id) {
    return buildView(
        'novo-produto',
        'Sprint Up | Novo produtos no Roadmap',
        'src.roadmap-products.roadmap-new-product-body',
        true,
        ['equipe_id' => $equipe_id]
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

Route::get('membros-equipe/{equipe_id}', function ($equipe_id) {
    return buildView(
        'membros-equipe',
        'Sprint Up | Membros da equipe',
        'src.team-members.team-members-body',
        true,
        ['equipe_id' => $equipe_id]
    );
});

Route::get('squads-equipe/{equipe_id}', function ($equipe_id) {
    return buildView(
        'squads-equipe',
        'Sprint Up | Squads da equipe',
        'src.team-squads.team-squads-body',
        true,
        ['equipe_id' => $equipe_id]
    );
});

Route::get('nova-squad/{equipe_id}', function ($equipe_id) {
    return buildView(
        'nova-squad',
        'Sprint Up | Nova Squads na equipe',
        'src.team-squads.new-squad-body',
        true,
        ['equipe_id' => $equipe_id]
    );
});

Route::get('aceitar-link-convite/{hash_convite}', function ($hash_convite) {
    return buildView(
        'aceitar-link-convite',
        'Sprint Up | Aceitar convite para a equipe',
        'src.team-invite-acception.team-invite-acception-body',
        false,
        ['hash_convite' => $hash_convite]
    );
});

Route::get('nova-equipe', function () {
    return buildView(
        'nova-equipe',
        'Sprint Up | Nova equipe',
        'src.new-team.new-team-body'
    );
});
