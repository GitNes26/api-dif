<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ObjResponse;
use App\Models\Situation;
use App\Models\VW_Situation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SituationController extends Controller
{
    /**
     * Mostrar lista de situaciones.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            // $list = VW_Situation::orderBy('id', 'desc');
            $list = Situation::with([
                'requester',
                'subcategory',
                // 'situationSetting',
                'register',
                'authorizer',
                'followUper',
                'rejecter',
                'familyData',
                'livingData',
                'economicData',
                'documentsData',
                'evidencesData'
            ])->orderBy('id', 'desc');
            if ($auth->role_id > 1) $list = $list->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de situaciones.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "SituationController ~ index ~ Hubo un error -> " . $ex->getMessage();
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
            $list = VW_Situation::where('active', true)
                ->select('id as id', 'situation as label')
                ->orderBy('situation', 'asc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de situaciones.';
            $response->data["alert_text"] = "Situaciones encontradas";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "SituationController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar situacion.
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
            $folio = $this->getLastFolio($request->letters);
            // var_dump($folio);
            $numFolio = 0;
            if ($folio != 0) {
                $parts = explode("-", $folio);
                $numFolio = (int)end($parts);
            }
            $numFolio += 1;
            $folio = sprintf("%s-%d", $request->letters, $numFolio);

            $situation = Situation::find($id);
            if (!$situation) {
                $situation = new Situation();
            }

            // $situation->fill($request->all());
            $situation->folio = $folio;
            $situation->requester_id = $request->requester_id;
            $situation->subcategory_id = $request->subcategory_id;
            $situation->registered_by = is_null($id) && $userAuth->id;
            $situation->description = $request->description;
            $situation->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | situacion editada.' : 'peticion satisfactoria | situacion registrada.';
            $response->data["alert_text"] = $id > 0 ? "Situación editada" : "Situación registrada";
        } catch (\Exception $ex) {
            $msg = "SituationController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    public function followUp(Request $request, Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $userAuth = Auth::user();
            // $duplicate = $this->validateAvailableData($request->full_name, $request->cellphone, $id);
            // if ($duplicate["result"] == true) {
            //     $response->data = $duplicate;
            //     return response()->json($response);
            // }

            $situation = Situation::find($id);
            Log::info("situacion: " . $situation);

            $situation->fill($request->all());
            // $situation->folio = $folio;
            // $situation->requester_id = $request->requester_id;
            $situation->save();
            Log::info("situacion editada: " . $situation);


            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'situacion editada.';
            $response->data["alert_text"] = "Sección completada";
        } catch (\Exception $ex) {
            $msg = "SituationController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar situacion.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, String $column, String $value, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $Situation = Situation::where($column, $value)->first();
            // Log::info("SitationController ~ show ~ Situation" . json_encode($Situation));
            $situation = $Situation::with([
                'requester',
                'beneficiary',
                'subcategory',
                // 'situationSetting',
                'register',
                'authorizer',
                'followUper',
                'rejecter',
                'familyData',
                'livingData',
                'economicData',
                'documentsData',
                'evidencesData'
            ])->findOrFail($Situation->id);

            if ($internal) return $situation;
            // Log::info("SitationController ~ show ~ situtation" . json_encode($situation));

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | situacion encontrada.';
            $response->data["result"] = $situation;
        } catch (\Exception $ex) {
            $msg = "SituationController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) situacion.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            Situation::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | situacion eliminada.";
            $response->data["alert_text"] = "Situación eliminada";
        } catch (\Exception $ex) {
            $msg = "SituationController ~ delete ~ Hubo un error -> " . $ex->getMessage();
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
            Situation::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | situacion $description.";
            $response->data["alert_text"] = "Situación $description";
        } catch (\Exception $ex) {
            $msg = "SituationController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
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
            Situation::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "SituationController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }


    // /**
    //  * Funcion para validar que campos no deben de duplicarse sus valores.
    //  * 
    //  * @return ObjRespnse|false
    //  */
    // private function validateAvailableData($full_name, $cellphone, $id)
    // {
    //     // #VALIDACION DE DATOS REPETIDOS
    //     $duplicate = $this->checkAvailableData('situations', 'full_name', $full_name, 'El nombre dla situacion', 'full_name', $id, null);
    //     if ($duplicate["result"] == true) return $duplicate;
    //     $duplicate = $this->checkAvailableData('situations', 'cellphone', $cellphone, 'El número celular dla situacion', 'cellphone', $id, null);
    //     if ($duplicate["result"] == true) return $duplicate;
    //     return array("result" => false);
    // }


    /**
     * Obtener el ultimo folio.
     *
     * @return \Illuminate\Http\Int $folio
     */
    private function getLastFolio(string $letters = null)
    {
        try {
            $folio = Situation::max('folio');
            if ($letters) $folio = Situation::where('folio', 'like', "$letters-%")->max('folio');

            return $folio ?? 0; // Si no hay folio, regresar 0
        } catch (\Exception $ex) {
            $msg =  "SitationController ~ getLastFolio ~ Error al obtener Ultimo Folio: " . $ex->getMessage();
            Log::error($msg);
            return $msg;
        }
    }
}