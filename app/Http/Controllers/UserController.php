<?php

namespace App\Http\Controllers;

use App\Models\ObjResponse;
use App\Models\User;
use App\Models\VW_User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function index(Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $list = VW_User::where("role_id", ">=", $role_id)
                ->orderBy('id', 'desc')
                ->get();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'Peticion satisfactoria | Lista de usuarios.';
            $response->data["result"] = $list;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar lista de usuarios activos por role
     * uniendo con roles.
     *
     * @return \Illuminate\Http\Response $response
     */
    public function indexByrole(Response $response, Int $role_id)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $roleAuth = Auth::user()->role_id;
            $signo = "=";
            $signo = $role_id == 2 && $roleAuth == 1 ? "<=" : "=";

            $list = VW_User::where('active', true)->where("role_id", $signo, $role_id)
                ->orderBy('id', 'desc')
                ->get();

            $response->data = ObjResponse::CorrectResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de usuarios.';
            $response->data["alert_text"] = "usuarios encontrados";
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
            $list = VW_User::where('active', true)
                ->select('id as id', 'username as label')
                ->orderBy('username', 'asc')->get();

            $response->data = ObjResponse::CorrectResponse();
            $response->data["message"] = 'peticion satisfactoria | lista de usuarios.';
            $response->data["alert_text"] = "usuarios encontrados";
            $response->data["result"] = $list;
            $response->data["toast"] = false;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Crear o Actualizar usuario.
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
            $duplicate = $this->validateAvailableData($request->username, $request->email, $request->id);
            if ($duplicate["result"] == true) {
                $response->data = $duplicate;
                return response()->json($response);
            }

            $user = User::find($request->id);
            if (!$user) $user = new User();
            $user->fill($request->only(['email', 'username', 'role_id']));
            if ((bool)$request->change_password && strlen($request->password) > 0) $user->password = Hash::make($request->password);
            $user->save();

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = $id > 0 ? 'peticion satisfactoria | usuario editado.' : 'peticion satisfactoria | usuario registrado.';
            $response->data["alert_text"] = $id > 0 ? "Usuario editado" : "Usuario registrado";
        } catch (\Exception $ex) {
            error_log("Hubo un error al crear o actualizar el usuario ->" . $ex->getMessage());
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Mostrar usuario.
     *
     * @param   int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function show(Request $request, Response $response, Int $id, bool $internal = false)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $id_user = $id;
            // if ($internal == 1) $id_user = $request->page_index;
            $user = VW_User::find($id_user);

            if ($internal) return $user;

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | usuario encontrado.';
            $response->data["result"] = $user;
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Activar o Desactivar" (cambiar estado activo) usuario.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function delete(Response $response,  Int $id, string $active)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            User::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | usuario $description.";
            $response->data["alert_text"] = "Usuario $description";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * "Activar o Desactivar" (cambiar estado activo) user.
     *
     * @param  int $id
     * @param  int $active
     * @return \Illuminate\Http\Response $response
     */
    public function disEnable(Response $response,  Int $id, string $active)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            User::where('id', $id)
                ->update([
                    'active' => $active === "reactivar" ? 1 : 0
                ]);

            $description = $active == "0" ? 'desactivado' : 'reactivado';
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = "peticion satisfactoria | user $description.";
            $response->data["alert_text"] = "Usuario $description";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Eliminar uno o varios usuarios.
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
            User::whereIn('id', $request->ids)->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
            $response->data = ObjResponse::CorrectResponse();
            $response->data["message"] = $countDeleted == 1 ? 'peticion satisfactoria | usuario eliminado.' : "peticion satisfactoria | usuarios eliminados ($countDeleted).";
            $response->data["alert_text"] = $countDeleted == 1 ? 'Usuario eliminado' : "Usuarios eliminados  ($countDeleted)";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }


    private function validateAvailableData($username, $email, $id)
    {
        // #VALIDACION DE DATOS REPETIDOS
        $duplicate = $this->checkAvailableData('users', 'username', $username, 'El nombre de usuario', 'username', $id, null);
        if ($duplicate["result"] == true) return $duplicate;
        $duplicate = $this->checkAvailableData('users', 'email', $email, 'El correo electrónico', 'email', $id, null);
        if ($duplicate["result"] == true) return $duplicate;
        return array("result" => false);
    }

    public function checkAvailableData($table, $column, $value, $propTitle, $input, $id, $secondTable = null)
    {
        if ($secondTable) {
            $query = "SELECT count(*) as duplicate FROM $table INNER JOIN $secondTable ON user_id=users.id WHERE $column='$value' AND active=1;";
            if ($id != null) $query = "SELECT count(*) as duplicate FROM $table t INNER JOIN $secondTable ON t.user_id=users.id WHERE t.$column='$value' AND active=1 AND t.id!=$id";
        } else {
            $query = "SELECT count(*) as duplicate FROM $table WHERE $column='$value' AND active=1";
            if ($id != null) $query = "SELECT count(*) as duplicate FROM $table WHERE $column='$value' AND active=1 AND id!=$id";
        }
        //   echo $query;
        $result = DB::select($query)[0];
        //   var_dump($result->duplicate);
        if ((int)$result->duplicate > 0) {
            // echo "entro al duplicate";
            $response = array(
                "result" => true,
                "status_code" => 409,
                "alert_icon" => 'warning',
                "alert_title" => "$propTitle no esta disponible!",
                "alert_text" => "$propTitle no esta disponible! - $value ya existe, intenta con uno diferente.",
                "message" => "duplicate",
                "input" => $input,
                "toast" => false
            );
        } else {
            $response = array(
                "result" => false,
            );
        }
        return $response;
    }
}