<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Donacion;
use App\Models\Reserva;
use App\Models\Organizacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Donor user
        $donante = User::create([
            'name' => 'Diego (Donante Continental)',
            'email' => 'donante@continental.edu.pe',
            'password' => Hash::make('password'),
            'role' => 'donante',
        ]);

        // 2. Create an Organization user
        $orgUser = User::create([
            'name' => 'Comedor Infantil Huancayo',
            'email' => 'comedor@continental.edu.pe',
            'password' => Hash::make('password'),
            'role' => 'organizacion',
        ]);

        // Create the organization profile
        $organizacion = Organizacion::create([
            'user_id' => $orgUser->id,
            'nombre' => 'Comedor Infantil de Huancayo',
            'direccion' => 'Jr. Ayacucho Nro 345, Huancayo',
            'telefono' => '954789123',
            'capacidad_diaria' => 5,
            'descripcion' => 'Organización sin fines de lucro dedicada a proveer almuerzos saludables a niños en situaciones vulnerables.',
        ]);

        // 3. Create mock donations
        $donacion1 = Donacion::create([
            'donante_id' => $donante->id,
            'tipo_alimento' => '20 Kg de Papas Nativas',
            'cantidad' => 20.00,
            'unidad' => 'kg',
            'fecha_vencimiento' => now()->addDays(5)->format('Y-m-d'),
            'estado' => 'disponible',
            'ubicacion_recojo' => 'Jr. Ancash 780, Huancayo',
        ]);

        $donacion2 = Donacion::create([
            'donante_id' => $donante->id,
            'tipo_alimento' => '15 Litros de Leche Entera pasteurizada',
            'cantidad' => 15.00,
            'unidad' => 'litros',
            'fecha_vencimiento' => now()->addDays(3)->format('Y-m-d'),
            'estado' => 'disponible',
            'ubicacion_recojo' => 'Jr. Real 1040, Huancayo',
        ]);

        $donacion3 = Donacion::create([
            'donante_id' => $donante->id,
            'tipo_alimento' => '40 Unidades de Pan de Trigo Artesanal',
            'cantidad' => 40.00,
            'unidad' => 'unidades',
            'fecha_vencimiento' => now()->addDays(1)->format('Y-m-d'),
            'estado' => 'reservada',
            'ubicacion_recojo' => 'Av. Giráldez 210, Huancayo',
        ]);

        // 4. Create mock reservation for donacion3
        Reserva::create([
            'donacion_id' => $donacion3->id,
            'organizacion_id' => $organizacion->id,
            'fecha_reserva' => now(),
            'estado' => 'pendiente',
        ]);
    }
}
