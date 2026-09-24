@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="space-y-8">

    <!-- Hero / Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <span>Catálogo de Productos</span>
                <span class="text-xs font-mono px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Colección: db.productos
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-400">
                Gestión integral de inventario con almacenamiento no relacional BSON en MongoDB.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('productos.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-950 bg-gradient-to-r from-emerald-400 to-teal-300 hover:from-emerald-300 hover:to-teal-200 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Crear Producto</span>
            </a>
        </div>
    </div>

    <!-- Stats KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Productos -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Productos</p>
                    <p class="mt-2 text-3xl font-black text-white font-mono">{{ $totalProductos }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-emerald-400">
                <span>Documentos registrados en Mongo</span>
            </div>
        </div>

        <!-- Card 2: Valor del Inventario -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Valor Inventario</p>
                    <p class="mt-2 text-3xl font-black text-emerald-400 font-mono">${{ number_format($valorInventario, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 flex items-center justify-center text-teal-400 border border-teal-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-400">
                <span>Cálculo dinámico precio * stock</span>
            </div>
        </div>

        <!-- Card 3: Stock Crítico -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Stock Bajo / Crítico</p>
                    <p class="mt-2 text-3xl font-black {{ $stockBajo > 0 ? 'text-amber-400' : 'text-slate-200' }} font-mono">{{ $stockBajo }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $stockBajo > 0 ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-slate-800 text-slate-400 border-slate-700' }} flex items-center justify-center border">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs {{ $stockBajo > 0 ? 'text-amber-400' : 'text-slate-400' }}">
                <span>Menos de 5 unidades en bodega</span>
            </div>
        </div>

        <!-- Card 4: Categorías Únicas -->
        <div class="glass-card p-5 rounded-2xl relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Categorías Activas</p>
                    <p class="mt-2 text-3xl font-black text-cyan-400 font-mono">{{ count($categoriasExistentes) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-cyan-400">
                <span>Colección indexada por categoría</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="glass-panel p-5 rounded-2xl">
        <form method="GET" action="{{ route('productos.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Search Input -->
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Buscar Producto</label>
                <div class="relative">
                    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Nombre, especificación o detalle..." class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-2.5 pl-10 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Categoría</label>
                <select name="categoria" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="">Todas las Categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}" {{ $categoriaSeleccionada === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Ordenar Por</label>
                <select name="orden" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="created_at" {{ $orden === 'created_at' ? 'selected' : '' }}>Más Recientes</option>
                    <option value="precio" {{ $orden === 'precio' ? 'selected' : '' }}>Precio</option>
                    <option value="stock" {{ $orden === 'stock' ? 'selected' : '' }}>Stock</option>
                    <option value="nombre" {{ $orden === 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 hover:text-white transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filtrar</span>
                </button>
                @if($buscar || $categoriaSeleccionada || $orden !== 'created_at')
                    <a href="{{ route('productos.index') }}" class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 border border-slate-700 text-slate-400 hover:text-rose-400 transition" title="Limpiar Filtros">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Panel -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800/80 bg-slate-900/50 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Producto & BSON ID</th>
                        <th class="py-4 px-6">Categoría</th>
                        <th class="py-4 px-6">Precio</th>
                        <th class="py-4 px-6">Stock</th>
                        <th class="py-4 px-6">Atributos Dinámicos (NoSQL)</th>
                        <th class="py-4 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50 text-sm">
                    @forelse($productos as $producto)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <!-- Product Name & Mongo ID -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <a href="{{ route('productos.show', $producto->id) }}" class="font-bold text-slate-100 hover:text-emerald-400 transition tracking-tight text-base">
                                        {{ $producto->nombre }}
                                    </a>
                                    @if($producto->descripcion)
                                        <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $producto->descripcion }}</p>
                                    @endif
                                    <div class="mt-1.5 flex items-center space-x-2">
                                        <span class="inline-flex items-center text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700/60" title="MongoDB ObjectId">
                                            _id: {{ $producto->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    {{ $producto->categoria ?? 'General' }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-bold text-base text-slate-100 font-mono">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                            </td>

                            <!-- Stock -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @php $estadoStock = $producto->estado_stock; @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $estadoStock['badge'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $producto->stock > 0 ? ($producto->stock < 5 ? 'bg-amber-400' : 'bg-emerald-400') : 'bg-red-400' }}"></span>
                                    {{ $estadoStock['texto'] }}
                                </span>
                            </td>

                            <!-- Dynamic NoSQL Attributes -->
                            <td class="py-4 px-6">
                                @if(!empty($producto->atributos) && is_array($producto->atributos))
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach(array_slice($producto->atributos, 0, 3) as $k => $v)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] bg-slate-800/80 text-slate-300 border border-slate-700/50">
                                                <strong class="text-emerald-400 font-medium mr-1">{{ ucfirst($k) }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}
                                            </span>
                                        @endforeach
                                        @if(count($producto->atributos) > 3)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-slate-800 text-slate-400">
                                                +{{ count($producto->atributos) - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 italic">Sin especificaciones</span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View Details -->
                                    <a href="{{ route('productos.show', $producto->id) }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-emerald-500/20 text-slate-300 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/30 transition" title="Ver Detalle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="p-2 rounded-xl bg-slate-800/80 hover:bg-cyan-500/20 text-slate-300 hover:text-cyan-400 border border-slate-700/60 hover:border-cyan-500/30 transition" title="Editar Producto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button triggers Modal -->
                                    <button type="button" onclick="confirmarEliminacion('{{ $producto->id }}', '{{ addslashes($producto->nombre) }}')" class="p-2 rounded-xl bg-slate-800/80 hover:bg-rose-500/20 text-slate-300 hover:text-rose-400 border border-slate-700/60 hover:border-rose-500/30 transition" title="Eliminar de MongoDB">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-800/60 flex items-center justify-center text-slate-500 mb-4 border border-slate-700/50">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-200">No se encontraron productos</h3>
                                    <p class="text-xs text-slate-400 mt-1">No hay documentos que coincidan con los criterios de búsqueda en la colección.</p>
                                    <div class="mt-5">
                                        <a href="{{ route('productos.create') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-500 text-slate-950 hover:bg-emerald-400 transition">
                                            Crear Primer Producto
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($productos->hasPages())
            <div class="p-4 border-t border-slate-800/80 bg-slate-900/30">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div id="modal-eliminar" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-fade-in">
    <div class="glass-card max-w-md w-full p-6 rounded-3xl border border-rose-500/20 shadow-2xl">
        <div class="flex items-center space-x-3 text-rose-400 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">¿Eliminar Producto?</h3>
                <p class="text-xs text-rose-300">Esta acción no se puede deshacer en MongoDB.</p>
            </div>
        </div>

        <p class="text-sm text-slate-300 mb-6">
            Estás a punto de eliminar el documento de <strong id="modal-producto-nombre" class="text-white"></strong> permanentemente de la colección <code class="text-emerald-400 font-mono text-xs">productos</code>.
        </p>

        <form id="form-eliminar" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="cerrarModalEliminacion()" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-600/30 transition">
                    Sí, Eliminar de Mongo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmarEliminacion(id, nombre) {
        const modal = document.getElementById('modal-eliminar');
        const nombreElement = document.getElementById('modal-producto-nombre');
        const form = document.getElementById('form-eliminar');

        nombreElement.textContent = nombre;
        form.action = `/productos/${id}`;
        modal.classList.remove('hidden');
    }

    function cerrarModalEliminacion() {
        const modal = document.getElementById('modal-eliminar');
        modal.classList.add('hidden');
    }

    // Cerrar modal al hacer click fuera
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-eliminar');
        if (e.target === modal) {
            cerrarModalEliminacion();
        }
    });
</script>
@endsection
