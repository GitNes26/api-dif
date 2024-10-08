<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    /**
     * Mostrar lista de menus activos.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $list = Menu::where('active', true)
                ->orderBy('id', 'desc')->get();
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de menus.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
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
            $list = Menu::where('active', true)
                ->select('id as id', 'cycle_name as label')
                ->orderBy('cycle_name', 'asc')->get();
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de menus';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar menus.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function createOrUpdatex(Request $request, Response $response, Int $id = null, bool $internal = false)
    {
        try {
            $response->data = ObjResponse::DefaultResponse();

            $settings = Menu::find($id);
            if (!$settings) $settings = new Menu();

            $settings->fill($request->all());

            $settings->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? "satisfactoria | menus actualizadas" : 'satisfactoria | menus registradas.';
            $response->data["alert_text"] = $id > 0 ? "Configuración actualizada" : "Configuración registrada";
            $response->data["result"] = $settings;
            if (!$internal) return response()->json($response, $response->data["status_code"]);
            else return $settings;
        } catch (\Exception $ex) {
            $msg =  "Error al crear o actualizar menus: " . $ex->getMessage();
            error_log("$msg");
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
            if (!$internal) return response()->json($response, $response->data["status_code"]);
            else return "$msg";
        }
    }

    /**
     * Crear o Actualizar ciclo.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function createOrUpdateCycle(Request $request, Response $response, Int $id = null, bool $internal = false)
    {
        try {
            $response->data = ObjResponse::DefaultResponse();

            $settings = Menu::find($id);
            if (!$settings) $settings = new Menu();

            $settings->cycle_name = $request->cycle_name;
            $settings->cycle_start = $request->cycle_start;
            $settings->cycle_end = $request->cycle_end;

            $settings->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? "satisfactoria | ciclo actualizado" : 'satisfactoria | ciclo registrado.';
            $response->data["alert_text"] = $id > 0 ? "Ajustes actualizado" : "Ajustes registrado, ya puedes realizar la configuración";
            $response->data["result"] = $settings;
            if (!$internal) return response()->json($response, $response->data["status_code"]);
            else return $settings;
        } catch (\Exception $ex) {
            $msg =  "Error al crear o actualizar ciclo: " . $ex->getMessage();
            error_log("$msg");
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
            if (!$internal) return response()->json($response, $response->data["status_code"]);
            else return "$msg";
        }
    }

    /**
     * Eliminar configuracion o menus.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            // echo "$request->ids";
            // $deleteIds = explode(',', $ids);
            $countDeleted = sizeof($request->ids);
            Menu::whereIn('id', $request->ids)->delete();
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | configuración eliminados ($countDeleted).";
            $response->data["alert_text"] = "Documentos de Becas eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }



    /**
     * Mostrar configuración.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $settings = Menu::find($request->id);

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | configuración encontrados.';
            $response->data["result"] = $settings;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }



    /**
     * Eliminar (cambiar estado activo=false) configuración.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function destroy(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            Menu::find($request->id)
                ->update([
                    'active' => false,
                    'deleted_at' => date('Y-m-d H:i:s'),
                ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | configuración eliminada.';
            $response->data["alert_text"] = 'Documentos de Becas eliminados';
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar configuración actual.
     *
     * @param   int $id
     * @return \Illuminate\Http\Response $response
     */
    public function getCurrent(Response $response, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $today = date('Y-m-d');
            $settings = Menu::where('active', true)->orderBy('id', 'desc')->first();
            // $settings = Menu::where('active', true)->where("cycle_start", "<=", $today)->where("cycle_end", ">=", $today)->orderBy('id', 'desc')->first();

            // $cycle = Menu::where('active', true)->where("cycle_start", "<=", $today)->where("cycle_end", ">=", $today)->orderBy('id', 'desc')->first();
            // echo $settings->toSql();
            if ((bool)$internal) return $settings;

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | configuración actual.';
            $response->data["result"] = $settings;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }
}