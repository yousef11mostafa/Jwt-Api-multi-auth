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
         $user=auth("admin-api")->user();
         return $this->respondWithToken($token,$user);



    }
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admin-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user=auth("admin-api")->user();
        return $this->respondWithToken($token,$user);
    }


    public function me()
    {
        return response()->json(auth('admin-api')->user());
    }


    public function logout()
    {
        auth()->logout();
        Admin::find(auth("admin-api")->user()->id)->delete();
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
    protected function respondWithToken($token,$user=null)
    {
        return response()->json([
            'user'=>$user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60,

        ]);
    }
}
