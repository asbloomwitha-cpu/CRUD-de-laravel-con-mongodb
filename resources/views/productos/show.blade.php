@extends('layouts.app')

@section('title', $producto->nombre . ' - Detalle MongoDB')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <nav class="flex items-center space-x-2 text-xs text-slate-400">
            <a href="{{ route('productos.index') }}" class="hover:text-emerald-400 transition">Productos</a>
            <span>/</span>
            <span class="text-slate-200">Detalle del Documento</span>
        </nav>
        <div class="flex items-center space-x-2">
            <a href="{{ route('productos.edit', $producto->id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Editar</span>
            </a>
            <a href="{{ route('productos.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Volver</span>
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl space-y-8">
        
        <!-- Header: Title, Category, BSON ID -->
        <div class="border-b border-slate-800/80 pb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            {{ $producto->categoria ?? 'General' }}
                        </span>
                        @php $estadoStock = $producto->estado_stock; @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $estadoStock['badge'] }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $producto->stock > 0 ? ($producto->stock < 5 ? 'bg-amber-400' : 'bg-emerald-400') : 'bg-red-400' }}"></span>
                            {{ $estadoStock['texto'] }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $producto->nombre }}</h1>
                </div>

                <!-- Price Display -->
                <div class="text-right">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Precio Unitario</p>
                    <p class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono tracking-tight">
                        ${{ number_format($producto->precio, 2) }}
                    </p>
                </div>
            </div>

            <!-- BSON Metadata strip -->
            <div class="mt-4 flex flex-wrap items-center gap-2 pt-3 border-t border-slate-800/50 text-xs">
                <span class="text-slate-400">Colección: <span class="text-slate-200 font-mono">db.productos</span></span>
                <span class="text-slate-600">•</span>
                <span class="text-slate-400">BSON ObjectId:</span>
                <span class="font-mono bg-slate-900 px-2 py-0.5 rounded text-emerald-300 border border-slate-800 select-all">
                    ObjectId("{{ $producto->id }}")
                </span>
            </div>
        </div>

        <!-- Description -->
        <div>
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Descripción del Producto</h3>
            <p class="text-sm text-slate-200 leading-relaxed bg-slate-900/40 p-4 rounded-2xl border border-slate-800/60">
                {{ $producto->descripcion ?: 'Este producto no cuenta con una descripción detallada en la base de datos.' }}
            </p>
        </div>

        <!-- Specifications & Dynamic Attributes Table -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Especificaciones Técnicas (Documento NoSQL)</span>
                </h3>
                <span class="text-xs text-slate-500 font-mono">Formato BSON Embebido</span>
            </div>

            @if(!empty($producto->atributos) && is_array($producto->atributos) && count($producto->atributos) > 0)
                <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-800/40 border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase">
                                <th class="py-3 px-5 w-1/3">Propiedad</th>
                                <th class="py-3 px-5">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($producto->atributos as $llave => $valor)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3 px-5 font-mono text-xs text-emerald-400 font-medium">
                                        {{ $llave }}
                                    </td>
                                    <td class="py-3 px-5 text-slate-200">
                                        {{ is_array($valor) ? json_encode($valor) : $valor }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-slate-900/30 border border-slate-800 text-xs text-slate-500 italic">
                    Sin atributos dinámicos adicionales registrados para este documento.
                </div>
            @endif
        </div>

        <!-- Raw MongoDB Document Inspector -->
        <div class="space-y-2">
            <button type="button" onclick="toggleJsonInspector()" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-400 hover:text-emerald-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                <span>Inspeccionar Documento BSON Crudo de MongoDB</span>
                <span id="json-toggle-icon" class="text-slate-500">▼</span>
            </button>

            <div id="json-inspector" class="hidden animate-fade-in">
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 font-mono text-xs text-emerald-300 overflow-x-auto shadow-inner">
                    <pre>{{ json_encode($producto->getAttributes(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>

        <!-- Timestamps & Metadata Footer -->
        <div class="border-t border-slate-800/80 pt-6 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                <p>Fecha de creación: <span class="text-slate-400 font-mono">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i:s') : 'N/A' }}</span></p>
                <p class="mt-0.5">Última actualización: <span class="text-slate-400 font-mono">{{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</span></p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('productos.edit', $producto->id) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 transition">
                    Editar Información
                </a>
                <button type="button" onclick="confirmarEliminacion('{{ $producto->id }}', '{{ addslashes($producto->nombre) }}')" class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition">
                    Eliminar Documento
                </button>
            </div>
        </div>

    </div>

</div>

<!-- Delete Modal -->
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
            Estás a punto de eliminar el documento de <strong id="modal-producto-nombre" class="text-white"></strong> permanentemente de la base de datos MongoDB.
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
    function toggleJsonInspector() {
        const inspector = document.getElementById('json-inspector');
        const icon = document.getElementById('json-toggle-icon');
        if (inspector.classList.contains('hidden')) {
            inspector.classList.remove('hidden');
            icon.textContent = '▲';
        } else {
            inspector.classList.add('hidden');
            icon.textContent = '▼';
        }
    }

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

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-eliminar');
        if (e.target === modal) {
            cerrarModalEliminacion();
        }
    });
</script>
@endsection
