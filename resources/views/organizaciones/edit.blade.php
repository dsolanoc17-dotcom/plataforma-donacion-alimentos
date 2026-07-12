<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Perfil de la Organización Receptora') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('organizaciones.update', $organizacion->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nombre de Org -->
                    <div>
                        <x-input-label for="nombre" :value="__('Nombre de la Organización')" />
                        <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $organizacion->nombre)" required autofocus />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Dirección -->
                    <div>
                        <x-input-label for="direccion" :value="__('Dirección Física')" />
                        <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion', $organizacion->direccion)" required />
                        <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                    </div>

                    <!-- Telefono -->
                    <div>
                        <x-input-label for="telefono" :value="__('Teléfono de Contacto')" />
                        <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $organizacion->telefono)" required />
                        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                    </div>

                    <!-- Capacidad Diaria -->
                    <div>
                        <x-input-label for="capacidad_diaria" :value="__('Capacidad Máxima de Reservas Activas')" />
                        <x-text-input id="capacidad_diaria" class="block mt-1 w-full" type="number" min="1" name="capacidad_diaria" :value="old('capacidad_diaria', $organizacion->capacidad_diaria)" required />
                        <span class="text-xs text-gray-500 mt-1 block">Número máximo de donaciones de alimentos que puede tener reservadas (no entregadas aún) al mismo tiempo.</span>
                        <x-input-error :messages="$errors->get('capacidad_diaria')" class="mt-2" />
                    </div>

                    <!-- Descripcion -->
                    <div>
                        <x-input-label for="descripcion" :value="__('Descripción / Misión')" />
                        <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white">{{ old('descripcion', $organizacion->descripcion) }}</textarea>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t border-gray-100">
                        <a href="{{ route('donaciones.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Volver al catálogo
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-full shadow transition-all">
                            Guardar Perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
