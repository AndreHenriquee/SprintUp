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
    string $bodyComponentName
) {
    return view(
        MAIN_VIEW,
        [
            'pageAlias' => $pageAlias,
            'title' => $pageTitle,
            'body' => $bodyComponentName,
        ]
    );
}

Route::get('/', function () {
    return buildView(
        'kanban',
        'Sprint Up Kanban',
        'src.kanban.kanban-body'
    );
});
