<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ Auth::user()->role === 'donante' ? __('Mis Donaciones Registradas') : __('Donaciones de Alimentos Disponibles') }}
            </h2>
            @if(Auth::user()->role === 'donante')
                <a href="{{ route('donaciones.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-full shadow transition-all hover:scale-105">
                    + Registrar Donación
                </a>
            @else
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        Capacidad diaria: <strong>{{ Auth::user()->organizacion->nombre }}</strong> ({{ Auth::user()->organizacion->capacidad_diaria }} max)
                    </span>
                    <a href="{{ route('organizaciones.edit', Auth::user()->organizacion->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                        Editar Perfil Org
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($donaciones->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500 text-lg">
                        {{ Auth::user()->role === 'donante' ? 'Aún no has registrado ninguna donación.' : 'No hay alimentos disponibles para donación en este momento.' }}
                    </p>
                    @if(Auth::user()->role === 'donante')
                        <a href="{{ route('donaciones.create') }}" class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-full shadow">
                            Registrar Mi Primera Donación
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($donaciones as $donacion)
                        @php
                            $daysToVence = now()->diffInDays(\Carbon\Carbon::parse($donacion->fecha_vencimiento), false);
                            if ($daysToVence < 0) {
                                $venceColor = 'bg-red-100 text-red-800 border-red-200';
                                $venceText = 'Vencido';
                            } elseif ($daysToVence <= 3) {
                                $venceColor = 'bg-orange-100 text-orange-800 border-orange-200';
                                $venceText = 'Vence pronto (' . $daysToVence . ' días)';
                            } else {
                                $venceColor = 'bg-green-100 text-green-800 border-green-200';
                                $venceText = 'Vence el ' . $donacion->fecha_vencimiento;
                            }
                        @endphp
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-column justify-between overflow-hidden">
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-800">
                                        ⚖️ {{ $donacion->cantidad }} {{ $donacion->unidad }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $venceColor }}">
                                        {{ $venceText }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $donacion->tipo_alimento }}</h3>

                                <div class="space-y-2 mt-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span>📍 Recojo:</span>
                                        <span class="font-medium text-gray-800">{{ $donacion->ubicacion_recojo }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>👤 Donante:</span>
                                        <span class="font-medium text-gray-800">{{ $donacion->donante->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>Estado:</span>
                                        <span class="capitalize px-2 py-0.5 rounded text-xs font-semibold
                                            {{ $donacion->estado === 'disponible' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $donacion->estado === 'reservada' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $donacion->estado === 'entregada' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
                                            {{ $donacion->estado }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                                @if(Auth::user()->role === 'donante')
                                    <a href="{{ route('donaciones.show', $donacion->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm px-3 py-1">
                                        Ver Detalles
                                    </a>
                                    @if($donacion->estado === 'disponible')
                                        <a href="{{ route('donaciones.edit', $donacion->id) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold text-sm px-3 py-1">
                                            Editar
                                        </a>
                                        <form action="{{ route('donaciones.destroy', $donacion->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar esta donación?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm px-3 py-1">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if($donacion->estado === 'disponible')
                                        <form action="{{ route('reservas.store') }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="donacion_id" value="{{ $donacion->id }}">
                                            <button type="submit" class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition-all">
                                                Reservar Donación
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
