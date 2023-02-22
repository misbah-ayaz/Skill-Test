<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DropdownController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/welcome', [DropdownController::class, 'index']);
Route::post('api/fetch-sellers', [DropdownController::class, 'fetchSeller']);
Route::post('api/fetch-steerings', [DropdownController::class, 'fetchSteering']);
Route::post('/welcome2', [DropdownController::class, 'welcome2']);
Route::post('api/fetch-sfxs', [DropdownController::class, 'fetchSFX']);
Route::post('api/fetch-variants', [DropdownController::class, 'fetchVariant']);
Route::post('api/fetch-colors', [DropdownController::class, 'fetchColor']);