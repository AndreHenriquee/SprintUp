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

Route::get('kanban', function () {
    return buildView(
        'kanban',
        'Sprint Up | Kanban',
        'src.kanban.kanban-body'
    );
});

Route::get('documentacoes', function () {
    return buildView(
        'documentacoes',
        'Sprint Up | Documentações',
        'src.documentacoes.documentacoes-body'
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

Route::get('procurar-documentos/{documentacao_tipo?}', function ($documentacao_tipo = null) {
    return buildView(
        'documentacao',
        'Sprint Up | Documentos',
        'src.documentacoes.documentacoes-body',
        true,
        ['documentacao_tipo' => $documentacao_tipo]
    );
});
