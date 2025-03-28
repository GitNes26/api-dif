<?php

namespace App\Http\Controllers;

use App\Models\FamilyData;
use App\Models\ObjResponse;
use App\Models\Situation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FamilyDataController extends Controller
{
    /**
     * Mostrar lista de informacion de familiares.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $list = FamilyData::orderBy('id', 'desc');
            if ($auth->role_id > 2) $list = $list->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de informacion de familiares.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ index ~ Hubo un error -> " . $ex->getMessage();
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
            $list = FamilyData::where('active', true)
                ->select('id as id', 'situation as label')
                ->orderBy('situation', 'asc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de informacion de familiares.';
            $response->data["alert_text"] = "Situaciones encontradas";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar lista de informacion de familiares.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function indexByFolio(Request $request, Response $response, String $folio)
    {
        // Log::info("folio: " . $folio);
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $situation = Situation::where('folio', $folio)->first();
            // Log::info("situation: " . $situation);

            $list = FamilyData::where("active", true)->where('situation_id', $situation->id)->orderBy('id', 'desc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de informacion de familiares.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ indexByFolio ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar informacion de familiar.
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
            $userAuth = Auth::user();
            // $duplicate = $this->validateAvailableData($request->full_name, $request->cellphone, $id);
            // if ($duplicate["result"] == true) {
            //     $response->data = $duplicate;
            //     return response()->json($response);
            // }

            $familyData = FamilyData::find($id);
            if (!$familyData) {
                $familyData = new FamilyData();
            }

            $familyData->fill($request->all());
            $familyData->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | informacion de familiar editado.' : 'peticion satisfactoria | informacion de familiar registrado.';
            $response->data["alert_text"] = $id > 0 ? "Familiar editado" : "Familiar registrado";
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar informacion de familiar.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, String $column, String $value, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $FamilyData = FamilyData::where($column, $value)->first();
            // Log::info("SitationController ~ show ~ FamilyData" . json_encode($FamilyData));
            $familyData = $FamilyData::with([
                'requester',
                'subcategory',
                // 'situationSetting',
                'register',
                'authorizer',
                'followUper',
                'rejecter',
                'familyData',
                'documentsData',
                'evidencesData'
            ])->findOrFail($FamilyData->id);

            if ($internal) return $familyData;
            // Log::info("SitationController ~ show ~ situtation" . json_encode($familyData));

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | informacion de familiar encontrada.';
            $response->data["result"] = $familyData;
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) informacion de familiar.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            FamilyData::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | informacion de familiar eliminada.";
            $response->data["alert_text"] = "Familiar eliminada";
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ delete ~ Hubo un error -> " . $ex->getMessage();
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
            FamilyData::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | informacion de familiar $description.";
            $response->data["alert_text"] = "Familiar $description";
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
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
            FamilyData::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "FamilyDataController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }
}
