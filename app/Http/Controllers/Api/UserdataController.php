<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Userdata;

class UserdataController extends ApiController
{
    public function getUsers() {
        $data = [];
        $users = Userdata::all();
        $data['users'] = $users;

        return $this->sendResponse($data, "Usuarios recuperados correctamente");
    }
}
