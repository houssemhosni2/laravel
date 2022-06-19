<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class userController extends Controller
{
    public function register(Request $request, $role_id)
    {
        !$user = User::where('email', $request['email'])->first();
        if ($user) {
            $response['status'] = 0;
            $response['message'] = 'Email deja exist !!';
            $response['code'] = 409;
        } else {
            Log::info($role_id);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $role_id
            ]);
            $response['status'] = 1;
            $response['message'] = 'Inscription a été effectué avec succès';
            $response['code'] = 200;
        }
        return response()->json($response);
    }

    public function login(Request $request, $role_id)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!JWTAuth::attempt($credentials)) {
                $response['status'] = 0;
                $response['code'] = 401;
                $response['message'] = 'Email ou Mot de passe Incorrect !!';
                return response()->json($response);
            }
        } catch (JWTException $e) {
            $response['data'] = null;
            $response['code'] = 500;
            $response['message'] = 'Erreur Token !!';
            return response()->json($response);
        }
        $user = auth()->user();

        if ($user->role_id != $role_id) {
            $response['status'] = 0;
            $response['data'] = null;
            $response['code'] = 405;
            $response['message'] = 'permission denied !!';
            return response()->json($response);
        }

        $data['token'] = auth()->claims([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ])->attempt($credentials);
        $response['data'] = $data;
        $response['user'] = $user;
        $response['status'] = 1;
        $response['code'] = 200;
        $response['message'] = 'Connexion effectuée';
        return response()->json($response);
    }
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    public function updateUser(Request $request, $id)
    {
        $user = user::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User Not Found'], 404);
        }
        if (isset($request->password))
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        else
            $user->update([
                'name' => $request->name,
            ]);
        return response()->json($user, 200);
    }
}
