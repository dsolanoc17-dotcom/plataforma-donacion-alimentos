<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'organizacion') {
            // Check that the profile exists
            if (!$user->organizacion) {
                abort(400, 'Falta perfil de organización.');
            }
            $reservas = Reserva::where('organizacion_id', $user->organizacion->id)
                ->with(['donacion.donante'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Donantes see reservations made on their donations
            $reservas = Reserva::whereHas('donacion', function ($query) use ($user) {
                $query->where('donante_id', $user->id);
            })
            ->with(['donacion', 'organizacion.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        }

        return view('reservas.index', compact('reservas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'organizacion') {
            abort(403, 'Solo las organizaciones pueden reservar alimentos.');
        }

        if (!$user->organizacion) {
            abort(400, 'Falta configurar su perfil de organización.');
        }

        $request->validate([
            'donacion_id' => 'required|exists:donaciones,id',
        ]);

        $donacion = Donacion::findOrFail($request->donacion_id);

        if ($donacion->estado !== 'disponible') {
            return redirect()->route('donaciones.index')->with('error', 'Esta donación ya no está disponible para reservar.');
        }

        // Check capacity of organization
        $activeReservasCount = Reserva::where('organizacion_id', $user->organizacion->id)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->count();

        if ($activeReservasCount >= $user->organizacion->capacidad_diaria) {
            return redirect()->route('donaciones.index')->with('error', 'Has alcanzado tu límite máximo de reservas diarias activas.');
        }

        // Change donation status to reservada
        $donacion->update(['estado' => 'reservada']);

        // Create reservation
        Reserva::create([
            'donacion_id' => $donacion->id,
            'organizacion_id' => $user->organizacion->id,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('reservas.index')->with('success', '¡Donación reservada con éxito! Por favor coordine el recojo.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $user = Auth::user();
        $request->validate([
            'estado' => 'required|string|in:confirmada,completada,cancelada',
        ]);

        $nuevoEstado = $request->estado;

        // Authorization checks
        if ($user->role === 'donante') {
            // Donantes can confirm or complete, or cancel
            if ($reserva->donacion->donante_id !== $user->id) {
                abort(403, 'No tienes permiso para actualizar esta reserva.');
            }
        } elseif ($user->role === 'organizacion') {
            // Organizacion can only cancel if it is pending
            if ($reserva->organizacion_id !== $user->organizacion->id) {
                abort(403, 'No tienes permiso para actualizar esta reserva.');
            }
            if ($nuevoEstado !== 'cancelada') {
                abort(403, 'Las organizaciones solo pueden cancelar sus propias reservas.');
            }
        }

        if ($nuevoEstado === 'cancelada') {
            // Release the donation back to disponible
            $reserva->donacion->update(['estado' => 'disponible']);
            $reserva->update(['estado' => 'cancelada']);
            return redirect()->route('reservas.index')->with('success', 'Reserva cancelada con éxito.');
        }

        if ($nuevoEstado === 'completada') {
            // Mark donation as entregada
            $reserva->donacion->update(['estado' => 'entregada']);
            $reserva->update(['estado' => 'completada']);
            return redirect()->route('reservas.index')->with('success', 'Reserva completada y alimento entregado.');
        }

        $reserva->update(['estado' => $nuevoEstado]);

        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }
}
