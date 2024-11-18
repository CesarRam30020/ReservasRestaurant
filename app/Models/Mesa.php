<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = "mesas";

    public static function getTableAvailable(string $fecha, int $comensales) {
        $reservas = Reserva::getBookings($fecha, $comensales);
        $mesasUsadas = $reservas->pluck('mesa_id');
        $mesas = Mesa::whereNotIn('id', $mesasUsadas)
            ->where('espacios', $comensales)
            ->first();
        return $mesas;
    }
}
