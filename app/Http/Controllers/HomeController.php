<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Reserva;
use Carbon\Carbon;
use DateException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private $answer = [
        'data' => '',
        'message' => '',
        'code' => 200,
    ];

    public function index() {
        return view('landing');
    }

    public function reservar(Request $request) {
        try {
            $answer = $this->answer;
            $comensales = $request['customers'];
            $fecha = Carbon::parse($request['date']);
            $mesa = Mesa::getTableAvailable($fecha, $comensales);
            $fechaActual = Carbon::now();

            if ($fechaActual > $fecha)
                throw new DateException(
                    'Lo sentimos, pero no es posible hacer una reservación para una fecha u hora anterior... A menos que sea usted un viajero del tiempo 😱',
                    400
                );

            if ($mesa == null)
                throw new NoTableException(
                    "No hay mesas para " . $comensales . " en el horario " . $fecha . ' intenta en otro horario u otro día',
                    400
                );

            DB::beginTransaction();
            $reserva = new Reserva();
            $reserva->mesa_id = $mesa->id;
            $reserva->comensales = $comensales;
            $reserva->fecha_hora = $fecha;
            $reserva->estatus = 'A';
            $reserva->save();

            $answer['data'] = $reserva;
            $answer['message'] = "Su reserva fue exitosa #Reserva: " . $reserva->id;
            $answer['code'] = 200;
            DB::commit();
        } catch (NoTableException | DateException $e) {
            $answer['message'] = $e->getMessage();
            $answer['code'] = $e->getCode();
        } catch (QueryException $qe) {
            DB::rollBack();
            $answer['message'] = 'Hubo un error al guardar tu reservación, por favor intentalo más tarde.';
            $answer['code'] = 500;
        } finally {
            return response()->json($answer, $answer['code']);
        }
    }

    public function reserva(int $id) {
        $answer = $this->answer;
        $reserva = Reserva::find($id);
        $answer['message'] = "Lo sentimos, no existe una reserva con dicho numero de reserva. Intento con uno diferente";
        $answer['code'] = 400;
        $answer['data'] = null;

        if ($reserva != null) {
            $answer['message'] = "La reserva fue encontrada";
            $answer['code'] = 200;
            $answer['data'] = $reserva;
        }

        return response()->json($answer, $answer['code']);
    }

    public function reservaCancelar(Request $request) {
        try {
            $answer = $this->answer;

            DB::beginTransaction();
            $reserva = Reserva::find($request['reserva_id']);
            $reserva->estatus = "C";
            $reserva->save();
            DB::commit();

            $answer['code'] = 200;
            $answer['message'] = "Reserva cancelada correctamente.";
        } catch (QueryException $qe) {
            DB::rollBack();
            $answer['code'] = 500;
            $answer['message'] = "No fue posible cancelar la venta, por favor intentelo más tarde.";
        } finally {
            return response()->json($answer, $answer['code']);
        }
    }
}

class NoTableException extends Exception {
    public function __construct(
        $message = "No se encontraror mesas disponibles",
        $code = 400,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}