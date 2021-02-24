<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;

class ActividadesController extends ApiController
{
    public function getActividades() {
        $data = [];
        $actividades = DB::table('actividad')
                        ->select('actividad.id', 'actividad.nombre', 'actividad.foto', 'actividad.fecha')
                        ->get();

        $data['actividades'] = $actividades;

        return $this->sendResponse($data, "Actividades recuperadas correctamente");
    }
}