<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Validator;
use App\Actividad;

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

    public function addActividad(Request $request) {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:actividad',
            'foto' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required',
        ]);

        if(!$validator) {
            return $this->sendError("Error de validación", $validator->errors(), 422);
        }

        $actividad = new Actividad();
        $actividad->nombre = $request->get('nombre');
        $actividad->foto = $request->get('foto');
        $actividad->descripcion = $request->get('descripcion');
        $actividad->fecha = $request->get('fecha');
        $actividad->save();

        $data = [
            "actividad" => $actividad,
        ];
        return $this->sendResponse($data, "Actividad creada correctamente");
    }
}