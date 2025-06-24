@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('notificaciones.index') }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Editar Notificación</h1>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <form action="{{ route('notificaciones.update', $notificacion) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Usuario Destinatario *
                    </label>
                    <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror">
                        <option value="">Selecciona un usuario</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" 
                                    {{ (old('user_id', $notificacion->user_id) == $usuario->id) ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título *
                    </label>
                    <input type="text" name="titulo" id="titulo" 
                           value="{{ old('titulo', $notificacion->titulo) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('titulo') border-red-500 @enderror"
                           placeholder="Ingresa el título de la notificación">
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="mensaje" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensaje *
                    </label>
                    <textarea name="mensaje" id="mensaje" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('mensaje') border-red-500 @enderror"
                              placeholder="Ingresa el mensaje de la notificación">{{ old('mensaje', $notificacion->mensaje) }}</textarea>
                    @error('mensaje')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="leido" value="1" 
                               {{ $notificacion->leido ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Marcar como leída</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('notificaciones.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Actualizar Notificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 