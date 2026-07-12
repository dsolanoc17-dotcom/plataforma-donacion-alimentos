<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->role === 'organizacion' ? __('Mis Reservas de Alimentos') : __('Reservas sobre mis Donaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($reservas->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500 text-lg">No hay reservas registradas.</p>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    <th class="p-6">Alimento</th>
                                    <th class="p-6">Cantidad</th>
                                    <th class="p-6">
                                        {{ Auth::user()->role === 'organizacion' ? 'Donante' : 'Organización Receptora' }}
                                    </th>
                                    <th class="p-6">Fecha de Reserva</th>
                                    <th class="p-6">Estado</th>
                                    <th class="p-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                                @foreach($reservas as $reserva)
                                    <tr>
                                        <td class="p-6 font-semibold text-gray-900">
                                            {{ $reserva->donacion->tipo_alimento }}
                                        </td>
                                        <td class="p-6">
                                            {{ $reserva->donacion->cantidad }} {{ $reserva->donacion->unidad }}
                                        </td>
                                        <td class="p-6">
                                            @if(Auth::user()->role === 'organizacion')
                                                {{ $reserva->donacion->donante->name }}
                                            @else
                                                {{ $reserva->organizacion->nombre }} (Tel: {{ $reserva->organizacion->telefono }})
                                            @endif
                                        </td>
                                        <td class="p-6">
                                            {{ $reserva->fecha_reserva }}
                                        </td>
                                        <td class="p-6">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize
                                                {{ $reserva->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $reserva->estado === 'confirmada' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $reserva->estado === 'completada' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $reserva->estado === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ $reserva->estado }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-right space-x-2">
                                            <a href="{{ route('donaciones.show', $reserva->donacion->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                Ver Detalles
                                            </a>
                                            
                                            @if(Auth::user()->role === 'donante' && $reserva->estado === 'pendiente')
                                                <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="estado" value="completada">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-semibold ms-2">
                                                        Entregar
                                                    </button>
                                                </form>
                                            @endif

                                            @if(Auth::user()->role === 'organizacion' && $reserva->estado === 'pendiente')
                                                <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro de cancelar esta reserva?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="estado" value="cancelada">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold ms-2">
                                                        Cancelar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
