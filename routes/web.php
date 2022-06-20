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

Route::get('roadmap', function () {
    return buildView(
        'roadmap',
        'Sprint Up | Roadmap',
        'src.roadmap.roadmap'
    );
});

Route::get('roadmap-cliente/{equipe_id}', function ($equipe_id) {
    return buildView(
        'roadmap',
        'Sprint Up | Roadmap - Visão do cliente',
        'src.roadmap.roadmap',
        false,
        ['equipe_id' => $equipe_id]
    );
});
