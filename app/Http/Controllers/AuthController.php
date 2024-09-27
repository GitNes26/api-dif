<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Metodo para validar credenciales e
     * inicar sesión
     * @param Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function login(Request $request, Response $response)
    {
        $field = 'username';
        $value = $request->username;
        if ($request->email) {
            $field = 'email';
            $value = $request->email;
        }

        $request->validate([
            $field => 'required',
            'password' => 'required'
        ]);
        $user = User::where("users.$field", "$value")->where('users.active', 1)
            ->join("roles", "users.role_id", "=", "roles.id")
            ->select("users.*", "roles.role", "roles.read", "roles.create", "roles.update", "roles.delete", "roles.more_permissions", "roles.page_index")
            ->orderBy('users.id', 'desc')
            ->first();


        if (!$user || !Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'message' => 'Credenciales incorrectas',
                'alert_title' => 'Credenciales incorrectas',
                'alert_text' => 'Credenciales incorrectas',
                'alert_icon' => 'error',
            ]);
        }
        $token = $user->createToken($user->email, [$user->role])->plainTextToken;
        // dd();
        $response->data = ObjResponse::SuccessResponse();
        $response->data["message"] = "peticion satisfactoria | usuario logeado. " . Auth::user();
        $response->data["result"]["token"] = $token;
        $response->data["result"]["auth"] = $user;
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Metodo para cerrar sesión.
     * @param int $id
     * @return \Illuminate\Http\Response $response
     */
    public function logout(Response $response, bool $all_sessions = false)
    {
        try {
            //  DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();
            if (!$all_sessions) Auth::user()->currentAccessToken()->delete(); #Elimina solo el token activo
            else auth()->user()->tokens()->delete(); #Utilizar este en caso de que el usuario desee cerrar sesión en todos lados o cambie informacion de su usuario / contraseña

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | sesión cerrada.';
            $response->data["alert_title"] = "Bye!";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Reegistrarse como Ciudadano.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function signup(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $duplicate = $this->validateAvailableData($request->username, $request->email, null);
            if ($duplicate["result"] == true) {
                $response->data = $duplicate;
                return response()->json($response);
            }

            $new_user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => 3,  //usuario normal - Ciudadano
            ]);
            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | usuario registrado.';
            $response->data["alert_text"] = "REGISTRO EXITOSO! <br>Bienvenido $request->username!";
        } catch (\Exception $ex) {
            $response->data = ObjResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response->data["status_code"]);
    }

    /**
     * Cambiar contraseña usuario.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function changePasswordAuth(Request $request, Response $response)
    {
        $response->data = ObjResponse::DefaultResponse();
        try {
            $userAuth = Auth::user();
            $user = User::find($userAuth->id);

            $response->data = ObjResponse::SuccessResponse();
            if (!Hash::check($request->password, $user->password)) {
                $response->data["message"] = 'peticion satisfactoria | la contraseña actual no es correcta.';
                $response->data["alert_icon"] = "error";
                $response->data["alert_text"] = "La contraseña actual que ingresas no es correcta";
                return response()->json($response, $response->data["status_code"]);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();
            auth()->user()->tokens()->delete(); #Utilizar este en caso de que el usuario desee cerrar sesión en todos lados o cambie informacion de su usuario / contraseña

            $response->data = ObjResponse::SuccessResponse();
            $response->data["message"] = 'peticion satisfactoria | contraseña actualizada.';
            $response->data["alert_text"] = "Contraseña actualizada - todas tus sesiones se cerraran para aplicar cambios.";
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
            $query = "SELECT count(*) as duplicate FROM $table INNER JOIN $secondTable ON id=users.id WHERE $column='$value' AND active=1;";
            if ($id != null) $query = "SELECT count(*) as duplicate FROM $table t INNER JOIN $secondTable ON t.id=users.id WHERE t.$column='$value' AND active=1 AND t.id!=$id";
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