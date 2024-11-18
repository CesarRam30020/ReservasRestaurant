<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = "reservas";

    public function mesa() {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }

    public static function getBookings(string $fecha, int $comensales) {
        $reservas = Reserva::with('mesa')
            ->where([
                ['estatus', 'A'],
                ['fecha_hora', $fecha],
            ])
            ->get();
        
        $reservas = $reservas->filter(function ($reserva) use ($comensales) {
            return $reserva->mesa->espacios == $comensales;
        });

        return $reservas;
    }
}
