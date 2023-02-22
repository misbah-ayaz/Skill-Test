<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DropdownController;
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

Route::get('/connection', function () {
    try {
        DB::connection()->getPdo();
        return "connection successful";
    } catch (\Exception $e) {
        die("Could not connect to the database.  Please check your configuration. error:" . $e );
    }
});

Route::get('/welcome', [DropdownController::class, 'index']);
Route::get('api/fetch-sellers', [DropdownController::class, 'fetchSeller']);
Route::get('api/fetch-steerings', [DropdownController::class, 'fetchSteering']);
Route::post('/welcome2', [DropdownController::class, 'welcome2']);
Route::post('api/fetch-sfxs', [DropdownController::class, 'fetchSFX']);
Route::post('api/fetch-variants', [DropdownController::class, 'fetchVariant']);
Route::post('api/fetch-colors', [DropdownController::class, 'fetchColor']);
Route::post('api/save-data', [DropdownController::class, 'saveData']);
Route::post('api/save_data', [DropdownController::class, 'save_data']);
Route::post('api/view_data', [DropdownController::class, 'view_data']);
Route::post('api/filter', [DropdownController::class, 'filter']);