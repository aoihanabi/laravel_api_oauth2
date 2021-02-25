<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Validator;
use App\Confirmacion;

class ConfirmacionController extends ApiController
{
    public function getConfirmaciones() {
        $data = [];
        $confirmaciones = DB::table('confirmacion')
                        ->select('confirmacion.id', 'confirmacion.iduser', 'confirmacion.idactividad')
                        ->get();

        $data['confirmaciones'] = $confirmaciones;

        return $this->sendResponse($data, "Confirmaciones recuperadas correctamente");
    }

    public function addConfirmacion(Request $request) {
        $validator = Validator::make($request->all(), [
            'iduser' => 'required',
            'idactividad' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError("Error de validación", $validator->errors(), 422);
        }

        $confirmacion_existente = Confirmacion::where([
            ["iduser", "=", $request->iduser],
            ["idactividad", "=", $request->idactividad],

        ])->first();

        if($confirmacion_existente !== null){
            return $this->sendError("Error de confirmación", "El usuario ya ha confirmado previamente", 422);
        }

        $confirmacion = new Confirmacion();
        $confirmacion->iduser = $request->get('iduser');
        $confirmacion->idactividad = $request->get('idactividad');
        $confirmacion->save();

        $data = [
            "confirmacion" => $confirmacion,
        ];
        return $this->sendResponse($data, "Confirmacion creada correctamente");
    }

    public function deleteConfirmacion(Request $request) {
        $id = $request->get("id");
        $confirmacion = Confirmacion::find($id);
        if($confirmacion === null) {
            return $this->sendError("Error en los datos provistos", ["La confirmacion indicada no existe"], 422);
        }

        $confirmacion->delete();

        return $this->sendResponse([
            "status" => "OK"
        ], "Confirmacion eliminada correctamente");

    }
}
