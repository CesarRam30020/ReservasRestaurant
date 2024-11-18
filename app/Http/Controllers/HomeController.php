<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        return view('landing');
    }

    public function reservar(Request $request) {
        try {
            $answer = [
                'data' => '',
                'message' => '',
                'code' => 200,
            ];
            $comensales = $request['customers'];
            $fecha = $request['date'];
            $mesa = Mesa::getTableAvailable($fecha, $comensales);

            if ($mesa == null)
                throw new NoTableException(
                    "No hay mesas para " . $comensales . " en el horario " . $fecha . ' intenta en otro horario u otro día',
                    400
                );

            DB::beginTransaction();
            $reserva = new Reserva();
            $reserva->mesa_id = $mesa->id;
            $reserva->no_reserva = 1;
            $reserva->comensales = $comensales;
            $reserva->fecha_hora = $fecha;
            $reserva->estatus = 'A';
            $reserva->save();
            DB::commit();
        } catch (NoTableException $nt) {
            $answer['message'] = $nt->getMessage();
            $answer['code'] = $nt->getCode();
        } catch (QueryException $qe) {
            DB::rollBack();
            $answer['message'] = 'Hubo un error al guardar tu reservación, por favor intentalo más tarde.';
            $answer['code'] = 500;
        } finally {
            return response()->json($answer, $answer['code']);
        }
    }
}

class NoTableException extends Exception {
    public function __construct(
        $message = "No se encontraror mesas disponibles",
        $code = 400, $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}