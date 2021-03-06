<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Validator;
use OneSignal;
use App\Confirmacion;
use App\Actividad;

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

    public function getConfirmacionDetail($id, Request $request) {
        $confirmacion = Confirmacion::find($id);
        if($confirmacion === null) {
            return $this->sendError("Error en los datos provistos", ["La confirmacion indicada no existe"], 422);
        }

        $actividad = Actividad::find($confirmacion->idactividad);
        if($actividad === null) {
            return $this->sendError("Error en los datos provistos", ["La actividad indicada no existe"], 422);
        }

        $users = DB::table('confirmacion')
                ->where('confirmacion.idactividad', '=', $confirmacion->idactividad)
                ->join('userdata', 'confirmacion.iduser', 'userdata.iduser')
                ->select("userdata.iduser", 'userdata.nombre', 'userdata.edad', 'userdata.genero')
                ->get();

        $data = [
            'actividad' => $actividad,
            'users' => $users
        ];
        return $this->sendResponse($data, "Confirmacion recuperadas correctamente");
    }
    
    public function getConfirmacionUser($id, Request $request) {
        $confirmaciones = DB::table('confirmacion')
                ->where('confirmacion.iduser', '=', $id)
                ->join('actividad', 'confirmacion.idactividad', 'actividad.id')
                ->select("confirmacion.id", 'actividad.id as idactividad', 'actividad.nombre', 'actividad.active',
                        'actividad.fecha', 'actividad.descripcion', 'actividad.foto')
                ->get();
        
        $data = [
            'confirmaciones' => $confirmaciones
        ];
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

        $users = DB::table('confirmacion')
                ->where('confirmacion.idactividad', '=', $confirmacion->idactividad)
                ->join('userdata', 'confirmacion.iduser', 'userdata.iduser')
                ->select("userdata.idonesignal")
                ->get();
        foreach($users as $user) {
            $id = $user->idonesignal;
            if ($id != 1) {
                OneSignal::sendNotificationToUser("Se a apuntado otro usuario a la actividad",
                $id,
                $data = null,
                $buttons = null,
                $schedule = null); //data buttons and schedule are examples of things I could send to the app receiving the notification
            }
        }

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
