<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;

    protected $table = 'organizaciones';

    protected $fillable = [
        'user_id',
        'nombre',
        'direccion',
        'telefono',
        'capacidad_diaria',
        'descripcion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'organizacion_id');
    }
}
