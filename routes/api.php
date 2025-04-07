<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CivilStatusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentDataController;
use App\Http\Controllers\EconomicDataController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvidenceDataController;
use App\Http\Controllers\FamilyDataController;
use App\Http\Controllers\LivingConditionsDataController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Models\Menu;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
    return "API DIF :)";
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkLoggedIn', function (Response $response, Request $request) {
        $response->data = ObjResponse::SuccessResponse();
        $id = Auth::user()->id;
        if ($id < 1 || !$id) {
            throw ValidationException::withMessages([
                'message' => false
            ]);
        }
        if ($request->url) {
            $response->data = ObjResponse::DefaultResponse();
            try {
                $menu = Menu::where('url', $request->url)->where('active', 1)->select("id")->first();
                $response->data = ObjResponse::SuccessResponse();
                $response->data["message"] = 'Peticion satisfactoria | Lista de menus.';
                $response->data["result"] = $menu;
            } catch (\Exception $ex) {
                $response->data = ObjResponse::CatchResponse($ex->getMessage());
            }
            return response()->json($response, $response->data["status_code"]);
        }
        return response()->json($response, $response->data["status_code"]);
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/changePasswordAuth', [AuthController::class, 'changePasswordAuth']);

    Route::prefix("menus")->group(function () {
        Route::get("/", [MenuController::class, 'index']);
        Route::get("/getMenusByRole/{pages_read}", [MenuController::class, 'getMenusByRole']);
        Route::get("/getHeadersMenusSelect", [MenuController::class, 'getHeadersMenusSelect']);
        Route::get("/selectIndexToRoles", [MenuController::class, 'selectIndexToRoles']);
        Route::post("/createOrUpdate/{id?}", [MenuController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [MenuController::class, 'show']);
        Route::get("/disEnable/{id}/{active}", [MenuController::class, 'disEnable']);

        Route::post("/getIdByUrl", [MenuController::class, 'getIdByUrl']);
    });

    Route::prefix("roles")->group(function () {
        Route::get("/", [RoleController::class, 'index']);
        Route::get("/selectIndex", [RoleController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [RoleController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [RoleController::class, 'show']);
        Route::get("/delete/{id}", [RoleController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [RoleController::class, 'disEnable']);
        Route::get("/deleteMultiple", [RoleController::class, 'deleteMultiple']);

        Route::post("/updatePermissions", [RoleController::class, 'updatePermissions']);
    });

    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, 'index']);
        Route::get("/selectIndexByRole/{role_id}", [UserController::class, 'selectIndexByRole']);
        Route::get("/selectIndex", [UserController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [UserController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [UserController::class, 'show']);
        Route::get("/delete/{id}", [UserController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [UserController::class, 'disEnable']);
        Route::get("/deleteMultiple", [UserController::class, 'deleteMultiple']);
    });

    Route::prefix("categories")->group(function () {
        Route::get("/", [CategoryController::class, 'index']);
        Route::get("/selectIndex", [CategoryController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [CategoryController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [CategoryController::class, 'show']);
        Route::get("/delete/{id}", [CategoryController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [CategoryController::class, 'disEnable']);
        Route::get("/deleteMultiple", [CategoryController::class, 'deleteMultiple']);
    });

    Route::prefix("subcategories")->group(function () {
        Route::get("/", [SubcategoryController::class, 'index']);
        Route::get("/selectIndex", [SubcategoryController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [SubcategoryController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [SubcategoryController::class, 'show']);
        Route::get("/delete/{id}", [SubcategoryController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [SubcategoryController::class, 'disEnable']);
        Route::get("/deleteMultiple", [SubcategoryController::class, 'deleteMultiple']);

        Route::get("/SP_affairsByDepartment/{department_id?}", [SubcategoryController::class, 'SP_affairsByDepartment']);
    });

    Route::prefix("departments")->group(function () {
        Route::get("/", [DepartmentController::class, 'index']);
        Route::get("/selectIndex", [DepartmentController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [DepartmentController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [DepartmentController::class, 'show']);
        Route::get("/delete/{id}", [DepartmentController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [DepartmentController::class, 'disEnable']);
        Route::get("/deleteMultiple", [DepartmentController::class, 'deleteMultiple']);
    });

    Route::prefix("positions")->group(function () {
        Route::get("/", [PositionController::class, 'index']);
        Route::get("/selectIndex", [PositionController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [PositionController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [PositionController::class, 'show']);
        Route::get("/delete/{id}", [PositionController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [PositionController::class, 'disEnable']);
        Route::get("/deleteMultiple", [PositionController::class, 'deleteMultiple']);
    });

    Route::prefix("employees")->group(function () {
        Route::get("/", [EmployeeController::class, 'index']);
        Route::get("/selectIndex", [EmployeeController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [EmployeeController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [EmployeeController::class, 'show']);
        Route::get("/delete/{id}", [EmployeeController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [EmployeeController::class, 'disEnable']);
        Route::get("/deleteMultiple", [EmployeeController::class, 'deleteMultiple']);
    });

    Route::prefix("civilStatuses")->group(function () {
        Route::get("/", [CivilStatusController::class, 'index']);
        Route::get("/selectIndex", [CivilStatusController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [CivilStatusController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [CivilStatusController::class, 'show']);
        Route::get("/delete/{id}", [CivilStatusController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [CivilStatusController::class, 'disEnable']);
        Route::get("/deleteMultiple", [CivilStatusController::class, 'deleteMultiple']);
    });

    Route::prefix("registers")->group(function () {
        Route::get("/", [PersonalInfoController::class, 'index']);
        Route::get("/selectIndex", [PersonalInfoController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [PersonalInfoController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [PersonalInfoController::class, 'show']);
        Route::get("/delete/{id}", [PersonalInfoController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [PersonalInfoController::class, 'disEnable']);
        Route::get("/deleteMultiple", [PersonalInfoController::class, 'deleteMultiple']);
    });

    Route::prefix("situations")->group(function () {
        Route::get("/", [SituationController::class, 'index']);
        Route::get("/selectIndex", [SituationController::class, 'selectIndex']);
        Route::post("/createOrUpdate/{id?}", [SituationController::class, 'createOrUpdate']);
        Route::post("/followUp/{id}", [SituationController::class, 'followUp']);
        Route::get("/{column}/{value}", [SituationController::class, 'show']);
        Route::post("/{id}/saveFirmRequester", [SituationController::class, 'saveFirmRequester']);

        // Route::get("/id/{id}", [SituationController::class, 'show']);
        // Route::get("/folio/{folio}", [SituationController::class, 'show']);
        Route::get("/delete/id/{id}", [SituationController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [SituationController::class, 'disEnable']);
        Route::get("/deleteMultiple", [SituationController::class, 'deleteMultiple']);
        Route::post("/{id}/authorizationOrRejection", [SituationController::class, 'authorizationOrRejection']);
        Route::get("/ciudadano/{personal_info_id}/history", [SituationController::class, 'history']);
    });
    Route::prefix("familyData")->group(function () {
        Route::get("/", [FamilyDataController::class, 'index']);
        Route::get("/indexByFolio/{folio}", [FamilyDataController::class, 'indexByFolio']);
        Route::post("/createOrUpdate/{id?}", [FamilyDataController::class, 'createOrUpdate']);
        Route::get("/delete/{id}", [FamilyDataController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [FamilyDataController::class, 'disEnable']);
        Route::post("/deleteMultiple", [FamilyDataController::class, 'deleteMultiple']);
    });
    Route::prefix("livingData")->group(function () {
        Route::get("/", [LivingConditionsDataController::class, 'index']);
        Route::get("/indexByFolio/{folio}", [LivingConditionsDataController::class, 'indexByFolio']);
        Route::post("/createOrUpdate/{id?}", [LivingConditionsDataController::class, 'createOrUpdate']);
        Route::get("/delete/{id}", [LivingConditionsDataController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [LivingConditionsDataController::class, 'disEnable']);
        Route::get("/deleteMultiple", [LivingConditionsDataController::class, 'deleteMultiple']);
    });
    Route::prefix("economicData")->group(function () {
        Route::get("/", [EconomicDataController::class, 'index']);
        Route::get("/indexByFolio/{folio}", [EconomicDataController::class, 'indexByFolio']);
        Route::post("/createOrUpdate/{id?}", [EconomicDataController::class, 'createOrUpdate']);
        Route::get("/delete/{id}", [EconomicDataController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [EconomicDataController::class, 'disEnable']);
        Route::get("/deleteMultiple", [EconomicDataController::class, 'deleteMultiple']);
    });
    Route::prefix("documentData")->group(function () {
        Route::get("/", [DocumentDataController::class, 'index']);
        Route::get("/id/{id}", [DocumentDataController::class, 'show']);
        Route::get("/indexByFolio/{folio}", [DocumentDataController::class, 'indexByFolio']);
        Route::post("/createOrUpdate/{id}/folio/{folio}", [DocumentDataController::class, 'createOrUpdate']);
        Route::post("/createOrUpdate/folio/{folio}", [DocumentDataController::class, 'createOrUpdate']);
        Route::get("/delete/{id}", [DocumentDataController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [DocumentDataController::class, 'disEnable']);
        Route::get("/deleteMultiple", [DocumentDataController::class, 'deleteMultiple']);
    });
    Route::prefix("evidenceData")->group(function () {
        Route::get("/", [EvidenceDataController::class, 'index']);
        Route::get("/id/{id}", [EvidenceDataController::class, 'show']);
        Route::get("/indexByFolio/{folio}", [EvidenceDataController::class, 'indexByFolio']);
        Route::post("/createOrUpdate/{id}/folio/{folio}", [EvidenceDataController::class, 'createOrUpdate']);
        Route::post("/createOrUpdate/folio/{folio}", [EvidenceDataController::class, 'createOrUpdate']);
        Route::get("/delete/{id}", [EvidenceDataController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [EvidenceDataController::class, 'disEnable']);
        Route::get("/deleteMultiple", [EvidenceDataController::class, 'deleteMultiple']);
    });
});