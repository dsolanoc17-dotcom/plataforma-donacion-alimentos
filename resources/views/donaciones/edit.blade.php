<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Donación de Alimentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('donaciones.update', $donacion->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tipo Alimento -->
                    <div>
                        <x-input-label for="tipo_alimento" :value="__('Nombre o Tipo de Alimento')" />
                        <x-text-input id="tipo_alimento" class="block mt-1 w-full" type="text" name="tipo_alimento" :value="old('tipo_alimento', $donacion->tipo_alimento)" required autofocus />
                        <x-input-error :messages="$errors->get('tipo_alimento')" class="mt-2" />
                    </div>

                    <!-- Cantidad y Unidad -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="cantidad" :value="__('Cantidad')" />
                            <x-text-input id="cantidad" class="block mt-1 w-full" type="number" step="0.01" min="0.01" name="cantidad" :value="old('cantidad', $donacion->cantidad)" required />
                            <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="unidad" :value="__('Unidad de Medida')" />
                            <select id="unidad" name="unidad" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white" required>
                                <option value="kg" {{ old('unidad', $donacion->unidad) === 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                <option value="litros" {{ old('unidad', $donacion->unidad) === 'litros' ? 'selected' : '' }}>Litros (L)</option>
                                <option value="unidades" {{ old('unidad', $donacion->unidad) === 'unidades' ? 'selected' : '' }}>Unidades</option>
                                <option value="paquetes" {{ old('unidad', $donacion->unidad) === 'paquetes' ? 'selected' : '' }}>Paquetes / Cajas</option>
                            </select>
                            <x-input-error :messages="$errors->get('unidad')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Fecha de Vencimiento -->
                    <div>
                        <x-input-label for="fecha_vencimiento" :value="__('Fecha de Vencimiento')" />
                        <x-text-input id="fecha_vencimiento" class="block mt-1 w-full" type="date" name="fecha_vencimiento" :value="old('fecha_vencimiento', $donacion->fecha_vencimiento)" required />
                        <x-input-error :messages="$errors->get('fecha_vencimiento')" class="mt-2" />
                    </div>

                    <!-- Ubicacion Recojo -->
                    <div>
                        <x-input-label for="ubicacion_recojo" :value="__('Ubicación de Recojo')" />
                        <x-text-input id="ubicacion_recojo" class="block mt-1 w-full" type="text" name="ubicacion_recojo" :value="old('ubicacion_recojo', $donacion->ubicacion_recojo)" required />
                        <x-input-error :messages="$errors->get('ubicacion_recojo')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t border-gray-100">
                        <a href="{{ route('donaciones.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-full shadow transition-all">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
