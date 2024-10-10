<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    Route::get('/checkLoggedIn', function (Response $response) {
        $response->data = ObjResponse::SuccessResponse();
        $id = Auth::user()->id;
        if ($id < 1 || !$id)
            throw ValidationException::withMessages([
                'message' => false
            ]);
        return response()->json($response, $response->data["status_code"]);
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/changePasswordAuth', [AuthController::class, 'changePasswordAuth']);

    Route::prefix("menus")->group(function () {
        Route::get("/", [MenuController::class, 'index']);
        Route::get("/getMenusByRole/{pages_read}", [MenuController::class, 'getMenusByRole']);
        Route::get("/getHeadersMenusSelect", [MenuController::class, 'getHeadersMenusSelect']);
        Route::post("/createOrUpdate/{id?}", [MenuController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [MenuController::class, 'show']);
        Route::get("/disEnable/{id}/{active}", [MenuController::class, 'disEnable']);
    });

    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, 'index']);
        Route::get("/indexByrole/{role_id}", [UserController::class, 'indexByrole']);
        Route::get("/selectIndex", [UserController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [UserController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [UserController::class, 'show']);
        Route::get("/delete/{id}", [UserController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [UserController::class, 'disEnable']);
        Route::get("/deleteMultiple", [UserController::class, 'deleteMultiple']);
    });
});
