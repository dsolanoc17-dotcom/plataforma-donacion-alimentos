<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    use HasFactory;

    protected $table = 'donaciones';

    protected $fillable = [
        'donante_id',
        'tipo_alimento',
        'cantidad',
        'unidad',
        'fecha_vencimiento',
        'estado',
        'ubicacion_recojo'
    ];

    public function donante()
    {
        return $this->belongsTo(User::class, 'donante_id');
    }

    public function reserva()
    {
        return $this->hasOne(Reserva::class, 'donacion_id');
    }
}
