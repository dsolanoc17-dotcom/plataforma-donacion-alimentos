<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'donacion_id',
        'organizacion_id',
        'fecha_reserva',
        'estado'
    ];

    public function donacion()
    {
        return $this->belongsTo(Donacion::class, 'donacion_id');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }
}
