<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Donacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitados_no_pueden_crear_donaciones()
    {
        $response = $this->get(route('donaciones.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_donante_puede_crear_donacion_con_datos_validos()
    {
        $donante = User::create([
            'name' => 'Donante Test',
            'email' => 'donante@test.com',
            'password' => bcrypt('password'),
            'role' => 'donante'
        ]);

        $response = $this->actingAs($donante)->post(route('donaciones.store'), [
            'tipo_alimento' => 'Manzanas frescas',
            'cantidad' => 15.5,
            'unidad' => 'kg',
            'fecha_vencimiento' => now()->addDays(5)->format('Y-m-d'),
            'ubicacion_recojo' => 'Jr. Puno 123, Huancayo'
        ]);

        $response->assertRedirect(route('donaciones.index'));
        $this->assertDatabaseHas('donaciones', [
            'tipo_alimento' => 'Manzanas frescas',
            'donante_id' => $donante->id
        ]);
    }

    public function test_donante_no_puede_editar_donacion_de_otro_donante()
    {
        $donante1 = User::create([
            'name' => 'Donante 1',
            'email' => 'd1@test.com',
            'password' => bcrypt('password'),
            'role' => 'donante'
        ]);

        $donante2 = User::create([
            'name' => 'Donante 2',
            'email' => 'd2@test.com',
            'password' => bcrypt('password'),
            'role' => 'donante'
        ]);

        $donacion = Donacion::create([
            'donante_id' => $donante1->id,
            'tipo_alimento' => 'Pescado fresco',
            'cantidad' => 10,
            'unidad' => 'kg',
            'fecha_vencimiento' => now()->addDays(2)->format('Y-m-d'),
            'ubicacion_recojo' => 'Mercado Central',
            'estado' => 'disponible'
        ]);

        $response = $this->actingAs($donante2)->get(route('donaciones.edit', $donacion->id));

        $response->assertStatus(403);
    }
}
