<?php

use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
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
Route::post("/utilisateur/inscription",[UserController::class,"inscription"]);

Route::post("/utilisateur/connexion",[UserController::class,"connexion"]);
Route::get("/produits",[ProduitsController::class,"index"]);

Route::get("/produits/{id}",[ProduitsController::class,"show"]);

Route::group(["Middleware" => ["auth:sanctum"]] , function(){
    Route::post("/produits",[ProduitsController::class,"store"]);
    Route::put("/produits/{id}",[ProduitsController::class,"update"]);
    Route::delete("/produits/{id}",[ProduitsController::class,"destroy"]);
});
