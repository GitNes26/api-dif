<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (Request $request) {
    return "API DIF";
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/checkLoggedIn', function (Request $request) {
    //     $id = Auth::user()->id;
    //     if ($id < 1)
    //         throw ValidationException::withMessages([
    //             'message' => false
    //         ]);
    //     return true;
    // });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/changePasswordAuth', [AuthController::class, 'changePasswordAuth']);

    Route::prefix("menus")->group(function () {
        Route::get("/", [MenuController::class, 'index']);
        Route::get("/id/{id}", [MenuController::class, 'show']);
        Route::get("/getMenusByRole/{pages_read}", [MenuController::class, 'getMenusByRole']);
        Route::get("/getHeadersMenusSelect", [MenuController::class, 'getHeadersMenusSelect']);
        Route::post("/createOrUpdate/{id?}", [MenuController::class, 'createOrUpdate']);
    });
});
