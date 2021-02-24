<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Validator;
use App\Userdata;
use App\User;

class UserdataController extends ApiController
{
    public function getUsers() {
        $data = [];
        //$users = Userdata::all();
        $users = DB::table('users')
                   ->join('userdata', 'users.id', '=', 'userdata.iduser')
                   ->select('users.id', 'userdata.nombre', 'userdata.foto', 'userdata.edad', 'userdata.genero')
                   ->get();

        $data['users'] = $users;

        return $this->sendResponse($data, "Usuarios recuperados correctamente");
    }

    public function getUserDetail($id, Request $request) {
        $data = [];

        $user = new User();
        $userdata = Userdata::where("iduser", "=", $id)->first();
        
        $data["user"] = $user->find($id);
        $data["userdata"] = $userdata;

        return $this->sendResponse($data, "Datos de usuario recuperados correctamente");
    }

    public function addUsers(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'edad' => 'required',
            'genero' => 'required',
            'acercade' => 'required',
        ]);

        if(!$validator) {
            return $this->sendError("Error de validación", $validator->errors(), 422);
        }

        $input = $request->all();
        $input["password"] = bcrypt($request->get("password"));
        $user = User::create($input);
        $token = $user->createToken("MyApp")->accessToken;

        $userdata = new Userdata();
        $userdata->nombre = $request->get('name');
        $userdata->foto = $request->get('foto');
        $userdata->edad = $request->get('edad');
        $userdata->genero = $request->get('genero');
        $userdata->acercade = $request->get('acercade');
        $userdata->iduser = $user->id;
        $userdata->save();

        $data = [
            "token" => $token,
            "user" => $user,
            "userdata" => $userdata,
        ];
        return $this->sendResponse($data, "Usuario creado correctamente");
    }

    public function updateUsers(Request $request) {
        $id = $request->get("id");
        $user = User::find($id);
        if($user === null) {
            return $this->sendError("Error en los datos provistos", ["El usuario indicado no existe"], 422);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'edad' => 'required',
            'genero' => 'required',
            'acercade' => 'required',
        ]);
        if($validator->fails()) {
            return $this->sendError("Error de validación", $validator->errors(), 422);
        }

        $user->name = $request->get("name");
        $user->save();

        $userdata = Userdata::where("iduser", "=", $id)->first();
        $userdata->nombre = $request->get("name");
        $userdata->edad = $request->get("edad");
        $userdata->genero = $request->get("genero");
        $userdata->acercade = $request->get("acercade");
        $userdata->save();

        $data = [
            "user" => $user,
            "userdata" => $userdata,
        ];
        return $this->sendResponse($data, "Usuario editado correctamente");
    }
}
