<?php

namespace App\Http\Controllers;

use App\Models\ObjResponse;
use App\Models\Receipt;
use App\Models\Situation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    /**
     * Mostrar lista de recibos.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $auth = Auth::user();
            // $list = Receipt::orderBy('id', 'desc');
            // if ($auth->role_id > 2) $list = $list->where("active", true);
            // $list = $list->get();

            $receiptSituationIds = Receipt::pluck('situation_id')->unique()->filter();
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
                'evidencesData',
                'receipt'
            ])->whereIn('id', $receiptSituationIds)->orderBy('id', 'desc');
            if ($auth->role_id > 2) $list = $list->where("active", true);
            $list = $list->get();



            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de Recibos.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ index ~ Hubo un error -> " . $ex->getMessage();
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
            $list = Receipt::where('active', true)
                ->select('id as id', DB::raw("CONCAT(num_folio) as label"))
                ->orderBy('department', 'asc')->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de Recibos.';
            $response->data["alert_text"] = "Recibos encontrados";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ selectIndex ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar recibos.
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
            $receipt = Receipt::find($id);
            if (!$receipt) $receipt = new Receipt();

            if ($request->folio) $folio = $request->folio;
            else {
                $folio = $this->getLastFolio(false);
                $folio += 1;
            }

            $receipt->fill($request->all());
            $receipt->num_folio = $folio;
            $receipt->authorized_by = $userAuth->id;
            $receipt->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | recibo editado.' : 'peticion satisfactoria | recibo registrado.';
            $response->data["alert_text"] = $id > 0 ? "Recibo editado" : "Recibo registrado";
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ createOrUpdate ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar recibo.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, Int $id, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $receipt = Receipt::find($id);
            if ($internal) return $receipt;

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | recibo encontrado.';
            $response->data["result"] = $receipt;
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ show ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Eliminar" (cambiar estado activo=0) recibo.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response, Int $id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            Receipt::where('id', $id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | recibo eliminado.";
            $response->data["alert_text"] = "Recibo eliminado";
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ delete ~ Hubo un error -> " . $ex->getMessage();
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
            Receipt::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | recibo $description.";
            $response->data["alert_text"] = "Recibo $description";
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ disEnable ~ Hubo un error -> " . $ex->getMessage();
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
            Receipt::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | registro eliminado.' : "peticion satisfactoria | registros eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Registro eliminada' : "Registros eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $msg = "ReceiptContrller ~ deleteMultiple ~ Hubo un error -> " . $ex->getMessage();
            Log::error($msg);
            $response->data = ObjResponse::CatchResponse($msg);
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Obtener el ultimo folio.
     *
     * @return \Illuminate\Http\Int $folio
     */
    public function getLastFolio(Response $response, bool $newFolio = true)
    {
        try {
            $folio = Receipt::where('active', true)->max('num_folio');
            // Log::info("getLastFolio ~ folio:" . $folio);
            if ((bool) $newFolio) {
                $folio += 1 ?? 1;

                $response->data = ObjResponse::SuccessResponse();
                $response->data["message"] = 'peticion satisfactoria | folio generado.';
                $response->data["alert_text"] = "Nuevo folio";
                $response->data["result"] = $folio;
                return response()->json($response, $response->data["status_code"]);
            } else return $folio ?? 0; // Si no hay folio, regresar 0
        } catch (\Exception $ex) {
            $msg =  "ReceiptController ~ getLastFolio ~ Error al obtener Ultimo Folio: " . $ex->getMessage();
            Log::error($msg);
            return $msg;
        }
    }
}
