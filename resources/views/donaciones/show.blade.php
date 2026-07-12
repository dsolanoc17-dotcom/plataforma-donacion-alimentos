<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de la Donación') }}
            </h2>
            <a href="{{ route('donaciones.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                &larr; Volver al catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-800 mb-2">
                            ⚖️ {{ $donacion->cantidad }} {{ $donacion->unidad }}
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900">{{ $donacion->tipo_alimento }}</h1>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize
                        {{ $donacion->estado === 'disponible' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $donacion->estado === 'reservada' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $donacion->estado === 'entregada' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $donacion->estado === 'vencida' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        Estado: {{ $donacion->estado }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-t border-b border-gray-100 mb-8">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Detalles de Recojo</h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p>📍 <strong>Ubicación:</strong> {{ $donacion->ubicacion_recojo }}</p>
                            <p>📅 <strong>Vence el:</strong> {{ $donacion->fecha_vencimiento }}</p>
                            <p>🕒 <strong>Publicado:</strong> {{ $donacion->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Detalles del Donante</h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p>👤 <strong>Nombre:</strong> {{ $donacion->donante->name }}</p>
                            <p>✉️ <strong>Contacto:</strong> {{ $donacion->donante->email }}</p>
                        </div>
                    </div>
                </div>

                @if($donacion->reserva)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-indigo-950 mb-3">Información de la Reserva</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-indigo-900 mb-6">
                            <div>
                                <p>🏢 <strong>Organización:</strong> {{ $donacion->reserva->organizacion->nombre }}</p>
                                <p>📞 <strong>Teléfono de contacto:</strong> {{ $donacion->reserva->organizacion->telefono }}</p>
                                <p>📍 <strong>Dirección de la Org:</strong> {{ $donacion->reserva->organizacion->direccion }}</p>
                            </div>
                            <div>
                                <p>📅 <strong>Reservado el:</strong> {{ $donacion->reserva->fecha_reserva }}</p>
                                <p>⚙️ <strong>Estado de Reserva:</strong> <span class="font-bold uppercase text-xs">{{ $donacion->reserva->estado }}</span></p>
                            </div>
                        </div>

                        <!-- Actions for Reservation status updates -->
                        @if(Auth::user()->role === 'donante' && $donacion->reserva->estado === 'pendiente')
                            <div class="flex gap-2">
                                <form action="{{ route('reservas.update', $donacion->reserva->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="completada">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition-all">
                                        ✓ Confirmar Entrega
                                    </button>
                                </form>

                                <form action="{{ route('reservas.update', $donacion->reserva->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="cancelada">
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2 px-4 rounded-xl transition-all border border-red-200">
                                        ✕ Rechazar Reserva
                                    </button>
                                </form>
                            </div>
                        @elseif(Auth::user()->role === 'organizacion' && $donacion->reserva->estado === 'pendiente')
                            <div>
                                <form action="{{ route('reservas.update', $donacion->reserva->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="cancelada">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition-all">
                                        Cancelar Mi Reserva
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
