<?php

namespace App\Http\Controllers;

use App\Models\EconomicData;
use App\Models\ObjResponse;
use App\Models\Situation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EconomicDataController extends Controller
{
    /**
     * Mostrar lista de informacion de economia.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $list = EconomicData::orderBy('id', 'desc');
            if ($auth->role_id > 2) $list = $list->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de informacion de economia.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ index ~ Hubo un error -> " . $ex->getMessage();
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
            $list = EconomicData::where('active', true)
                ->select('id as id', 'situation as label')
                ->orderBy('situation', 'asc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de informacion de economia.';
            $response->data["alert_text"] = "Situaciones encontradas";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar lista de informacion de economia.
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

            $list = EconomicData::where("active", true)->where('situation_id', $situation->id)->orderBy('id', 'desc')->first();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de informacion de economia.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ indexByFolio ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar informacion de economica.
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

            $economicData = EconomicData::find($id);
            if (!$economicData) {
                $economicData = new EconomicData();
            }

            $economicData->fill($request->all());
            $economicData->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | informacion de economica editado.' : 'peticion satisfactoria | informacion de economica registrado.';
            $response->data["alert_text"] = $id > 0 ? "Datos Economicos editado" : "Datos Economicos registrado";
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar informacion de economica.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, String $column, String $value, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $EconomicData = EconomicData::where($column, $value)->first();
            // Log::info("SitationController ~ show ~ EconomicData" . json_encode($EconomicData));
            $economicData = $EconomicData::with([
                'requester',
                'beneficiary',
                'subcategory',
                // 'situationSetting',
                'register',
                'authorizer',
                'followUper',
                'rejecter',
                'economicData',
                'documentsData',
                'evidencesData'
            ])->findOrFail($EconomicData->id);

            if ($internal) return $economicData;
            // Log::info("SitationController ~ show ~ situtation" . json_encode($economicData));

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | informacion de economica encontrada.';
            $response->data["result"] = $economicData;
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) informacion de economica.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            EconomicData::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | informacion de economica eliminada.";
            $response->data["alert_text"] = "Datos Economicos eliminada";
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ delete ~ Hubo un error -> " . $ex->getMessage();
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
            EconomicData::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | informacion de economica $description.";
            $response->data["alert_text"] = "Datos Economicos $description";
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
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
            EconomicData::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "EconomicDataController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }
}
