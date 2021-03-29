<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['as' => 'api.'], function () {
    Route::get('/cocktails', 'CocktailController@index')->name('cocktails.index');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::apiResource('/cocktails', 'CocktailController', ['except' => ['index', 'show']]);
        Route::apiResource('/ingredients', 'IngredientController', ['except' => 'show']);
    });
});


