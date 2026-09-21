@extends('layouts.sidebaradmin')

@section('tituloPagina', 'Listados de Usuario')


@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-4">
    <div class="px-6 py-4 border-b border-slate-200 bg-emerald-50/50 flex items-center justify-between">
        <h3 class="font-heading font-bold text-slate-800 text-lg flex items-center gap-2">
            <i class="fas fa-users text-emerald-500"></i> Listado de Usuarios
        </h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table id="usuariosTable" class="w-full text-sm text-left text-slate-600 border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3 rounded-tl-lg">ID</th>
                        <th class="px-4 py-3">usuarios</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Editar</th>
                        <th class="px-4 py-3 text-center rounded-tr-lg">Eliminar</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-slate-100">
                    @forelse($usuarios as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-slate-500 font-medium">#{{ $user->id }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    {{ $user->role ? ucfirst($user->role->nombre) : 'Sin rol' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($user->estado == 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center" x-data="{ openEditModal: false }">
                                <!-- Botón que lanza el modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Launch demo modal
                                </button>

                                <!-- Modal de Edición (Tailwind + Alpine) -->
                                <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                    <div @click.away="openEditModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden text-left"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                                        
                                        <!-- Header -->
                                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                            <h3 class="font-bold text-slate-800 text-lg">Editar Usuario: {{ $user->name }}</h3>
                                            <button @click="openEditModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Body -->
                                        <div class="p-6">
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
                                                        <input type="text" value="{{ $user->name }}" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 outline-none transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                                                        <input type="email" value="{{ $user->email }}" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 outline-none transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Rol</label>
                                                        <select class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 outline-none transition-all">
                                                            <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Administrador</option>
                                                            <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Vendedor</option>
                                                            <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Cliente</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <!-- Footer -->
                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" @click="openEditModal = false" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded-xl transition-colors">
                                                        Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

    </div>
</div>

@endsection