<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class AuthController extends Controller
{
    public function testOauth () {
        $user = Auth::user();
        return response()->json([
            'user' => $user
        ], 200);
    }
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if(!$validator) {
            return response()->json (
                ["error"=>$validator->errors()], 422
            );
        }

        $input = $request->all();
        $input["password"] = bcrypt($request->get("password"));
        $user = User::create($input);
        $token = $user->createToken("MyApp")->accessToken;

        return response()->json (
            [
                "token" => $token,
                "user" => $user,
            ], 
            200
        );
    }
}
