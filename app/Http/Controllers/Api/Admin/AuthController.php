<?php

namespace App\Http\Controllers\Api\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Http\Requests\AdminRegisterRequest;

class AuthController extends Controller
{


    public function register(AdminRegisterRequest $request){

        $vaildData=$request->validated();

         $user=Admin::create([
            'name'=>$vaildData['name'],
            'email'=>$vaildData['email'],
            'password'=>bcrypt($vaildData['password'])
         ]);

         $token=auth('admin-api')->login($user);
         return $this->respondWithToken($token);


    }
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admin-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function me()
    {
        return response()->json(auth('admin-api')->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60
        ]);
    }
}
