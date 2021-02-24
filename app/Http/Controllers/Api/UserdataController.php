<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Userdata;
use App\User;

class UserdataController extends ApiController
{
    public function getUsers() {
        $data = [];
        $users = Userdata::all();
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
}
