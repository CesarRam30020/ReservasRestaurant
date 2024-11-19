<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    private $answer = [
        "data" => "",
        "message" => "",
        "code" => 200
    ];

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

    public function editarMesas(Request $request) {
        try {
            $answer = $this->answer;
            
            DB::beginTransaction();
            if ($request['mesa_id'] != ""){
                $mesa = Mesa::find($request['mesa_id']);
                $accion = "editada";
            } else {
                $mesa = new Mesa();
                $accion = "creada";
            }

            $mesa->nombre = $request['nom'];
            $mesa->descripcion = $request['desc'];
            $mesa->espacios = $request['espacios'];
            $mesa->save();

            $answer['data'] = $mesa->id;
            $answer['message'] = "Mesa ". $accion ." correctamente.";
            $answer['code'] = 200;
            DB::commit();
        } catch (QueryException $qe) {
            DB::rollBack();
            $answer['message'] = "No se pudo guardar la mesa, intentelo más tarde.";
            $answer['code'] = 500;
        } catch (Exception $e) {
            $answer['code'] = 500;
            $answer['message'] = $e->getMessage();
        } finally {
            return response()->json($answer, $answer['code']);
        }
        
    }
}
