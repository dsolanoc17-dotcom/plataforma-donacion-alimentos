<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'donante') {
            // Donantes see their own donations
            $donaciones = Donacion::where('donante_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Organizaciones see available donations
            $donaciones = Donacion::where('estado', 'disponible')
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();
        }

        return view('donaciones.index', compact('donaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'donante') {
            abort(403, 'Solo los donantes pueden crear donaciones.');
        }

        return view('donaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'donante') {
            abort(403, 'Solo los donantes pueden crear donaciones.');
        }

        $request->validate([
            'tipo_alimento' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date|after:today',
            'ubicacion_recojo' => 'required|string|max:255',
        ]);

        Donacion::create([
            'donante_id' => Auth::id(),
            'tipo_alimento' => $request->tipo_alimento,
            'cantidad' => $request->cantidad,
            'unidad' => $request->unidad,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'estado' => 'disponible',
            'ubicacion_recojo' => $request->ubicacion_recojo,
        ]);

        return redirect()->route('donaciones.index')->with('success', '¡Donación registrada con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donacion $donacione)
    {
        // Accept either $donacione (matching model binding name)
        $donacion = $donacione;
        return view('donaciones.show', compact('donacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donacion $donacione)
    {
        $donacion = $donacione;
        if (Auth::id() !== $donacion->donante_id) {
            abort(403, 'No estás autorizado para editar esta donación.');
        }

        if ($donacion->estado !== 'disponible') {
            return redirect()->route('donaciones.index')->with('error', 'Solo se pueden editar donaciones con estado disponible.');
        }

        return view('donaciones.edit', compact('donacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donacion $donacione)
    {
        $donacion = $donacione;
        if (Auth::id() !== $donacion->donante_id) {
            abort(403, 'No estás autorizado para editar esta donación.');
        }

        if ($donacion->estado !== 'disponible') {
            return redirect()->route('donaciones.index')->with('error', 'Solo se pueden editar donaciones con estado disponible.');
        }

        $request->validate([
            'tipo_alimento' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date|after:today',
            'ubicacion_recojo' => 'required|string|max:255',
        ]);

        $donacion->update($request->only([
            'tipo_alimento',
            'cantidad',
            'unidad',
            'fecha_vencimiento',
            'ubicacion_recojo'
        ]));

        return redirect()->route('donaciones.index')->with('success', 'Donación actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donacion $donacione)
    {
        $donacion = $donacione;
        if (Auth::id() !== $donacion->donante_id) {
            abort(403, 'No estás autorizado para eliminar esta donación.');
        }

        if ($donacion->estado !== 'disponible') {
            return redirect()->route('donaciones.index')->with('error', 'Solo se pueden eliminar donaciones con estado disponible.');
        }

        $donacion->delete();

        return redirect()->route('donaciones.index')->with('success', 'Donación eliminada con éxito.');
    }
}
