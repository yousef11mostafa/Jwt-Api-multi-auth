<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AuthController;


Route::prefix("admin")->group(function(){
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
});

Route::middleware('auth:admin-api')->prefix('admin')->group(function(){
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

    Route::get("test",function(){
        return response()->json([
            'status'=>"test admin route"
        ]);
    });

});
