<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Donacion;
use App\Models\Organizacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizacion_puede_reservar_donacion_disponible()
    {
        $donante = User::create([
            'name' => 'Donante Test',
            'email' => 'donante@test.com',
            'password' => bcrypt('password'),
            'role' => 'donante'
        ]);

        $donacion = Donacion::create([
            'donante_id' => $donante->id,
            'tipo_alimento' => 'Plátanos',
            'cantidad' => 50,
            'unidad' => 'unidades',
            'fecha_vencimiento' => now()->addDays(4)->format('Y-m-d'),
            'ubicacion_recojo' => 'Almacen Wanka',
            'estado' => 'disponible'
        ]);

        $orgUser = User::create([
            'name' => 'Organizacion Test',
            'email' => 'org@test.com',
            'password' => bcrypt('password'),
            'role' => 'organizacion'
        ]);

        $organizacion = Organizacion::create([
            'user_id' => $orgUser->id,
            'nombre' => 'Asilo de Ancianos',
            'direccion' => 'Jr. Tacna 123',
            'telefono' => '987654321',
            'capacidad_diaria' => 3
        ]);

        $response = $this->actingAs($orgUser)->post(route('reservas.store'), [
            'donacion_id' => $donacion->id
        ]);

        $response->assertRedirect(route('reservas.index'));
        
        $this->assertDatabaseHas('donaciones', [
            'id' => $donacion->id,
            'estado' => 'reservada'
        ]);

        $this->assertDatabaseHas('reservas', [
            'donacion_id' => $donacion->id,
            'organizacion_id' => $organizacion->id,
            'estado' => 'pendiente'
        ]);
    }

    public function test_donante_no_puede_reservar_donacion()
    {
        $donante = User::create([
            'name' => 'Donante Test',
            'email' => 'donante@test.com',
            'password' => bcrypt('password'),
            'role' => 'donante'
        ]);

        $donacion = Donacion::create([
            'donante_id' => $donante->id,
            'tipo_alimento' => 'Naranjas',
            'cantidad' => 20,
            'unidad' => 'kg',
            'fecha_vencimiento' => now()->addDays(4)->format('Y-m-d'),
            'ubicacion_recojo' => 'Almacen',
            'estado' => 'disponible'
        ]);

        $response = $this->actingAs($donante)->post(route('reservas.store'), [
            'donacion_id' => $donacion->id
        ]);

        $response->assertStatus(403);
    }
}
