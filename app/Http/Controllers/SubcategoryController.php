<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ObjResponse;
use App\Models\Subcategory;
use App\Models\VW_Employee;
use App\Models\VW_Subcategory;
use App\Models\VW_User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubcategoryController extends Controller
{
    public function SP_affairsByDepartment(Request $request, Response $response, Int $department_id = null)
    {
        // Log::info('SP_affairsByDepartment ~ department_id: ' . $department_id);
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $userEmployee = VW_User::where('id', $auth->id)->first();
            // Log::info('SP_affairsByDepartment ~ userEmployee: ' . $userEmployee);

            $list = VW_Subcategory::orderBy('department', 'asc')->orderBy('category', 'asc')->orderBy('subcategory', 'asc');
            $list = $list->get();
            if ($auth->role_id > 3 && $userEmployee && !\Str::contains($userEmployee->more_permissions, ['Ver Todas las Situaciones', 'todas'])) $list = DB::select("call sp_affairs_by_department(?)", [$userEmployee->department_id]);
            // Log::info('SP_affairsByDepartment ~ list: ' . $list);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de subcategorias.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ SP_affairsByDepartment ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }



    /**
     * Mostrar lista de subcategorias.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $userEmployee = VW_User::where('id', $auth->id)->first();

            $list = VW_Subcategory::orderBy('id', 'desc');
            // if ($auth->role_id > 2) $list = $list->where("active", true);
            if ($auth->role_id > 3 && $userEmployee && !\Str::contains($userEmployee->more_permissions, ['Ver Todas las Situaciones', 'todas'])) $list = $list->where("department_id", $userEmployee->department_id)->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de subcategorias.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ index ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar listado para un selector.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function selectIndex(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            // $list = VW_Subcategory::where('active', true)
            //     ->select('id as id', 'subcategory as label')
            //     ->orderBy('subcategory', 'asc')->get();


            $auth = Auth::user();
            $userEmployee = VW_User::where('id', $auth->id)->first();

            $list = VW_Subcategory::where('active', true)
                ->select('id as id', 'subcategory as label')
                ->orderBy('subcategory', 'asc');
            if ($auth->role_id > 3 && $userEmployee && !\Str::contains($userEmployee->more_permissions, ['Ver Todas las Situaciones', 'todas'])) $list = $list->where("department_id", $userEmployee->department_id);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de subcategorias.';
            $response->data["alert_text"] = "Subcategorias encontradas";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar subcategoria.
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $id
     * 
     * @return \Illuminate\Http\Response $response
     */
    public function createOrUpdate(Request $request, Response $response, Int $id = null)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $duplicate = $this->validateAvailableData($request->subcategory, $id);
            if ($duplicate["result"] == true) {
                $response->data = $duplicate;
                return response()->json($response);
            }

            $subcategory = Subcategory::find($id);
            if (!$subcategory) $subcategory = new Subcategory();

            $subcategory->fill($request->all());
            $subcategory->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | subcategoria editada.' : 'peticion satisfactoria | subcategoria registrada.';
            $response->data["alert_text"] = $id > 0 ? "Subcategoria editada" : "Subcategoria registrada";
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar subcategoria.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, Int $id, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $subcategory = VW_Subcategory::find($id);
            if ($internal) return $subcategory;

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | subcategoria encontrada.';
            $response->data["result"] = $subcategory;
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) subcategoria.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            Subcategory::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | subcategoria eliminada.";
            $response->data["alert_text"] = "Subcategoria eliminada";
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ delete ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Activar o Desactivar" (cambiar estado activo=1/0).
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function disEnable(Response $response, Int $id, string $active)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            Subcategory::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | subcategoria $description.";
            $response->data["alert_text"] = "Subcategoria $description";
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Eliminar uno o varios registros.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function deleteMultiple(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            // echo "$request->ids";
            // $deleteIds = explode(',', $ids);
            $countDeleted = sizeof($request->ids);
            Subcategory::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "SubcategoryController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }


    /**
     * Funcion para validar que campos no deben de duplicarse sus valores.
     * 
     * @return ObjRespnse|false
     */
    private function validateAvailableData($subcategory, $id)
    {
        // #VALIDACION DE DATOS REPETIDOS
        $duplicate = $this->checkAvailableData('subcategories', 'subcategory', $subcategory, 'La subcategoria', 'subcategory', $id, null);
        if ($duplicate["result"] == true) return $duplicate;
        return array("result" => false);
    }
}