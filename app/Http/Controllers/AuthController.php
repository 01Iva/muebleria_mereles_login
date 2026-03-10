<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="Laravel API", version="1.0.0")
 * @OA\Server(url="http://127.0.0.1:8000")
 * @OA\SecurityScheme(securityScheme="bearerAuth", type="http", scheme="bearer")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@test.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login exitoso"),
     *     @OA\Response(response=401, description="Credenciales incorrectas")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Credenciales incorrectas"], 401);
        }

        $token = $user->createToken("api-token")->plainTextToken;

        return response()->json([
            "message" => "Login exitoso",
            "token"   => $token,
            "role"    => $user->role
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Sesión cerrada")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Sesión cerrada correctamente"]);
    }
}