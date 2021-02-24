<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;

class AuthController extends ApiController
{
    public function testOauth () {
        $user = Auth::user();
        return $this->sendResponse($user, "Usuario recuperado correctamente");
    }
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if(!$validator) {
            return $this->sendError("Error de validación", $validator->errors(), 422);
        }

        $input = $request->all();
        $input["password"] = bcrypt($request->get("password"));
        $user = User::create($input);
        $token = $user->createToken("MyApp")->accessToken;

        $data = [
            "token" => $token,
            "user" => $user,
        ];
        return $this->sendResponse($data, "Usuario registrado correctamente");
    }
}
