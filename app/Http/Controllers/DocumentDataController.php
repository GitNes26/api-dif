<?php

namespace App\Http\Controllers;

use App\Models\DocumentData;
use App\Models\ObjResponse;
use App\Models\Situation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentDataController extends Controller
{
    /**
     * Mostrar lista de documentos.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            $list = DocumentData::orderBy('id', 'desc');
            if ($auth->role_id > 1) $list = $list->where("active", true);
            $list = $list->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de documentos.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ index ~ Hubo un error -> " . $ex->getMessage();
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
            $list = DocumentData::where('active', true)
                ->select('id as id', 'situation as label')
                ->orderBy('situation', 'asc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de documentos.';
            $response->data["alert_text"] = "Situaciones encontradas";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar lista de documentos.
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

            $list = DocumentData::where("active", true)->where('situation_id', $situation->id)->orderBy('id', 'desc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de documentos.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ indexByFolio ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar documento.
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $id
     * 
     * @return \Illuminate\Http\Response $response
     */
    public function createOrUpdate(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $userAuth = Auth::user();
            // $duplicate = $this->validateAvailableData($request->full_name, $request->cellphone, $id);
            // if ($duplicate["result"] == true) {
            //     $response->data = $duplicate;
            //     return response()->json($response);
            // }

            $documentData = DocumentData::find($request->id);
            if (!$documentData) {
                $documentData = new DocumentData();
            }

            $documentData->fill($request->all());
            $documentData->save();
            // $date = new Date();

            $this->ImageUp($request, 'img_doc', "situations/$request->folio", null, "$request->name_doc", $request->id == null ? true : false, "noImage.png", $documentData);


            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $request->id > 0 ? 'peticion satisfactoria | documento editado.' : 'peticion satisfactoria | documento registrado.';
            $response->data["alert_text"] = $request->id > 0 ? "Documento editado" : "Documento registrado";
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar documento.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, Int $id, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $documentData = DocumentData::find($id);
            // Log::info("SitationController ~ show ~ documentData" . json_encode($documentData));

            if ($internal) return $documentData;
            // Log::info("SitationController ~ show ~ situtation" . json_encode($documentData));

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | documento encontrada.';
            $response->data["result"] = $documentData;
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) documento.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            DocumentData::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | documento eliminada.";
            $response->data["alert_text"] = "Documento eliminada";
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ delete ~ Hubo un error -> " . $ex->getMessage();
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
            DocumentData::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | documento $description.";
            $response->data["alert_text"] = "Documento $description";
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
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
            DocumentData::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminado' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "DocumentDataController ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }
}