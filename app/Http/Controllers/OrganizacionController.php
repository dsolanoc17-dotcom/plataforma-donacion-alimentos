<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizacionController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organizacion $organizacione)
    {
        $organizacion = $organizacione;
        if (Auth::user()->role !== 'organizacion' || Auth::user()->organizacion->id !== $organizacion->id) {
            abort(403, 'No estás autorizado para editar este perfil.');
        }

        return view('organizaciones.edit', compact('organizacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organizacion $organizacione)
    {
        $organizacion = $organizacione;
        if (Auth::user()->role !== 'organizacion' || Auth::user()->organizacion->id !== $organizacion->id) {
            abort(403, 'No estás autorizado para editar este perfil.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'capacidad_diaria' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
        ]);

        $organizacion->update($request->only([
            'nombre',
            'direccion',
            'telefono',
            'capacidad_diaria',
            'descripcion'
        ]));

        return redirect()->route('donaciones.index')->with('success', '¡Perfil de organización actualizado con éxito!');
    }
}
