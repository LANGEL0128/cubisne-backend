<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="API Test",
 *     version="1.0.0",
 *     description="API CuBisne - La aplicación para todos los negocios cubanos",
 *     @OA\Contact(
 *          email="lanlion000128@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

class AuthController extends Controller
{
    /**
    * @OA\Post(
    *      path="/api/auth/login",
    *      operationId="login",
    *      tags={"Auth"},
    *      summary="Loguear el usuario",
    *      description="Loguear el usuario",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 schema="Request",
    *                 required={"email", "password"},
    *                 @OA\Property(title="Usuario", property="email", type="string", example="admin@admin.com"),
    *                 @OA\Property(title="Contraseña", property="password", type="string", example="admin"),
    *             )
    *         )
    *      ),
    *      @OA\Response(response=200, description="User logged", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (
            !User::where(['email' => request()->post('email'), 'is_active' => true])->exists() ||
            !$token = auth()->attempt($credentials)
        ) {
            return $this->sendError('Credenciales Inválidas', [], 401);
        }
        $user = User::with([
            'business',
            'business.services',
            'business.products',
            'roles',
        ])->where('id', auth()->user()->id)->firstOrFail();
        return $this->sendResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ], 'User Logged');
    }

    /**
    * @OA\Get(
    *      path="/api/auth/me",
    *      operationId="authMe",
    *      tags={"Auth"},
    *      summary="Mostrar Datos del Usuario",
    *      description="Mostrar Datos del Usuario logueado",
    *      security={{"bearerAuth":{}}},
    *      @OA\Response( response=200, description="Success", @OA\JsonContent()),
    *      @OA\Response( response=401, description="Unahutorized", @OA\JsonContent()),
    * )
    */
    public function me()
    {
        $user = User::with([
            'business',
            'business.services',
            'business.products',
            'roles',
        ])->where('id', auth()->user()->id)->firstOrFail();
        return $this->sendResponse(['user' => $user], 'User Logged');
    }

    /**
    * @OA\Post(
    *      path="/api/auth/profile",
    *      operationId="profile",
    *      tags={"Auth"},
    *      security={{"bearerAuth":{}}},
    *      summary="Editar perfil",
    *      description="Editar perfil",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 schema="Request",
    *                 required={"name", "lastname", "email"},
    *                 @OA\Property(title="Nombre", property="name", type="string", example="Luis"),
    *                 @OA\Property(title="Apellidos", property="lastname", type="string", example="Pérez"),
    *                 @OA\Property(title="Celular", property="phone", type="string", example="55667788"),
    *                 @OA\Property(title="Correo", property="email", type="string", example="admin@admin.com"),
    *                 @OA\Property(title="Contraseña", property="password", type="string", example="admin"),
    *                 @OA\Property(title="Imagen", property="photo", type="string", format="binary"),
    *             )
    *         )
    *      ),
    *      @OA\Response(response=200, description="Profile Edited", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function profile()
    {
        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $validatedData = Validator::make(request()->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'phone' => 'unique:users,phone,'.$user->id,
        ], [
            'name.required' => 'El nombre es requerido',
            'lastname.required' => 'El apellido es requerido',
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El correo ya existe en la base de datos',
            'phone.unique' => 'El celular ya existe en la base de datos',
        ]);
        if($validatedData->fails()) {
            return $this->sendError($validatedData->errors()->first(), [], 400);
        }
        $params = request()->post();
        if(!empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        } else {
            unset($params['password']);
        }
        $params['is_active'] = $user->is_active;
        if(request()->hasFile('photo') && request()->file('photo')->isValid()) {
            $params['photo'] = request()->file('photo')->store('uploads/users', 'public');
        }
        $user->update($params);
        $user = User::with([
            'business',
            'business.services',
            'business.products',
            'roles',
        ])->where('id', auth()->user()->id)->firstOrFail();
        return $this->sendResponse(['user' => $user], 'El perfil fue actualizado satisfactoriamente');
    }

    /**
    * @OA\Post(
    *      path="/api/auth/register",
    *      operationId="register",
    *      tags={"Auth"},
    *      summary="Registrar usuario",
    *      description="Registrar usuario",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 schema="Request",
    *                 required={"name", "lastname", "email", "password"},
    *                 @OA\Property(title="Nombre", property="name", type="string", example="Luis"),
    *                 @OA\Property(title="Apellidos", property="lastname", type="string", example="Pérez"),
    *                 @OA\Property(title="Correo", property="email", type="string", example="admin@admin.com"),
    *                 @OA\Property(title="Celular", property="phone", type="string", example="66773388"),
    *                 @OA\Property(title="Contraseña", property="password", type="string", example="Contraseña"),
    *                 @OA\Property(title="Confirmar Contraseña", property="password_confirmation", type="string", example="Contraseña"),
    *                 @OA\Property(title="Imagen", property="photo", type="string", format="binary"),
    *             )
    *         )
    *      ),
    *      @OA\Response(response=200, description="Usuario Registrado", @OA\JsonContent()),
    *      @OA\Response(response=400, description="Bad Request", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function register()
    {
        $validatedData = Validator::make(request()->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es requerido',
            'lastname.required' => 'El apellido es requerido',
            'email.required' => 'El correo es requerido',
            'email.email' => 'Debe ser un correo válido',
            'email.unique' => 'El correo ya existe en la base de datos',
            'phone.unique' => 'El celular ya existe en la base de datos',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe ser mínimo de 6 caracteres',
            'password.confirmed' => 'La contraseña no se ha confirmado correctamente',
        ]);

        if($validatedData->fails()) {
            return $this->sendError($validatedData->errors()->first(), [], 400);
        }
        Log::debug(request()->post());
        $params = request()->post();
        $params['password'] = Hash::make($params['password']);
        $user = User::create($params);
        $user->assignRole('cliente');
        Auth::login($user);
        $token = JWTAuth::fromUser($user);
        $user = User::with([
            'business',
            'business.services',
            'business.products',
            'roles',
        ])->where('id', auth()->user()->id)->firstOrFail();

        return $this->sendResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ], 'User Logged');
    }

    /**
    * @OA\Get(
    *      path="/api/auth/logout",
    *      operationId="logout",
    *      tags={"Auth"},
    *      summary="Desloguear al usuario",
    *      description="Desloguear al usuario",
    *      security={{"bearerAuth":{}}},
    *      @OA\Response(response=200, description="Logout", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function logout()
    {
        auth()->logout();
        return $this->sendResponse(['message' => 'Successfully logged out'], 'Successfully logged out');
    }

    /**
    * @OA\Get(
    *      path="/api/auth/refresh",
    *      operationId="refresh",
    *      tags={"Auth"},
    *      summary="Refrescar Access Token",
    *      description="Refrescar Access Token",
    *      security={{"bearerAuth":{}}},
    *      @OA\Response(response=200, description="Refresh Token", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function refresh()
    {
        $user = User::with([
            'business',
            'business.services',
            'business.products',
            'roles',
        ])->where('id', auth()->user()->id)->firstOrFail();
        return $this->sendResponse([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ], 'Token Refresh');
    }

    /**
    * @OA\Post(
    *      path="/api/auth/password/email",
    *      operationId="sendResetLinkEmail",
    *      tags={"Auth"},
    *      summary="Enviar link recupere contraseña",
    *      description="Enviar link recupere contraseña",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 schema="Request",
    *                 required={"email"},
    *                 @OA\Property(title="Correo", property="email", type="string", example="admin@admin.com"),
    *             )
    *         )
    *      ),
    *      @OA\Response(response=200, description="Link enviado", @OA\JsonContent()),
    *      @OA\Response(response=400, description="Bad Request", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), [], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->sendResponse([], __($status));
        }

        return $this->sendError(__($status), [], 400);
    }

    /**
    * @OA\Post(
    *      path="/api/auth/password/reset",
    *      operationId="resetPasword",
    *      tags={"Auth"},
    *      summary="Recuperar Contraseña",
    *      description="Recuperar Contraseña",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 schema="Request",
    *                 required={"email", "token", "password", "password_confirmation"},
    *                 @OA\Property(title="Correo", property="email", type="string", example="admin@admin.com"),
    *                 @OA\Property(title="Token", property="token", type="string", example="dfsdgsfewr"),
    *                 @OA\Property(title="Contraseña", property="password", type="string", example="123456"),
    *                 @OA\Property(title="Confirmar Contraseña", property="password_confirmation", type="string", example="123456"),
    *             )
    *         )
    *      ),
    *      @OA\Response(response=200, description="Contraseña Restablecida", @OA\JsonContent()),
    *      @OA\Response(response=400, description="Bad Request", @OA\JsonContent()),
    *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
    *  )
    */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.required' => 'El correo es requerido',
            'token.required' => 'El token es requerido',
            'token.required' => 'El token es requerido',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), [], 400);
        }

        $status = Password::reset(
            $request->only('email', 'token', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->sendResponse([], __($status));
        }

        return $this->sendError(__($status), [], 400);
    }

}


