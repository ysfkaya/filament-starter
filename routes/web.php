<?php

use App\Http\Controllers\PageController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blog', fn () => 'Blog');
Route::get('/blog/{slug}', fn () => 'Blog post')->name('post');

Route::get('{slug}', PageController::class)->where(['slug' => '^(?!'.ltrim(config('filament.path'), '/').'|filament|'.ltrim(config('telescope.path')).').*$'])->name('page');
