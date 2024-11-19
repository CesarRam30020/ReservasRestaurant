<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function reservas(Request $request) {
        $querys = [];
        
        if (isset($request['comensales']) && $request['comensales'] != 0)
            array_push($querys, ['comensales', $request['comensales']]);

        if (isset($request['estatus']) && $request['estatus'] != '-1')
            array_push($querys, ['estatus', $request['estatus']]);

        $reservas = Reserva::where($querys)->get();

        if (isset($request['fecha']) && $request['fecha'] != null){
            $fecha = Carbon::parse($request['fecha']);
            $reservas = $reservas->whereBetween('fecha_hora', [
                $fecha->startOfDay(),
                $fecha->copy()->endOfDay()
            ]);
        }

        return view('app.reservas', [
            "reservas" => $reservas,
            "comensales" => $request['comensales'],
            "fecha" => $request['fecha'],
            "estatus" => $request['estatus'],
        ]);
    }

    public function mesas(Request $request) {
        $querys = [];

        if (isset($request['comensales']) && $request['comensales'] != 0)
            array_push($querys, ['espacios', $request['comensales']]);

        if (isset($request['nombre']) && $request['nombre'] != "")
            array_push($querys, ['nombre', 'like', '%'.$request['nombre'].'%']);

        if (isset($request['descripcion']) && $request['descripcion'] != "")
            array_push($querys, ['descripcion', 'like', '%'.$request['descripcion'].'%']);

        $mesas = Mesa::where($querys)->get();
        return view('app.mesas', [
            "mesas" => $mesas
        ]);
    }
}
