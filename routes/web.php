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

function buildView(array $view_parameters)
{
    return view(
        MAIN_VIEW,
        $view_parameters
    );
}

Route::get('/', function () {
    return buildView([
        'title' => 'Sprint Up Home',
        'body' => 'src.home-body',
    ]);
});
