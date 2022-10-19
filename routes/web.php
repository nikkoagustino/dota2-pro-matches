<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

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

Route::get('pro', [APIController::class, 'getOlderProMatches']);
Route::get('pro-count', [APIController::class, 'getProMatchesCount']);

Route::get('hero', [APIController::class, 'getHeroes']);
Route::get('hero-d', [APIController::class, 'getHeroesDesc']);
Route::get('hero-name', [APIController::class, 'getHeroesName']);

Route::get('hero-ajax', function(){
    return view('ajax');
});

Route::get('hero-rand', [APIController::class, 'getHeroesRand']);