@extends('layouts.app')

@section('title', 'Nuevo Producto NoSQL')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex items-center space-x-2 text-xs text-slate-400 mb-3">
            <a href="{{ route('productos.index') }}" class="hover:text-emerald-400 transition">Productos</a>
            <span>/</span>
            <span class="text-slate-200">Crear Documento</span>
        </nav>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Registrar Nuevo Producto</h1>
                <p class="text-sm text-slate-400 mt-1">Inserta un nuevo documento BSON dentro de la colección <code class="text-emerald-400 font-mono">db.productos</code>.</p>
            </div>
            <a href="{{ route('productos.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Volver al Catálogo</span>
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200">
            <div class="flex items-center space-x-3 mb-2">
                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="text-sm font-bold text-rose-300">Por favor corrige los siguientes errores:</h4>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-200/90 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('productos.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Panel 1: Información Básica -->
        <div class="glass-panel p-6 sm:p-8 rounded-3xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                <span class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xs font-mono font-bold">1</span>
                <span>Información General</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Nombre del Producto <span class="text-emerald-400">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Laptop ASUS ZenBook Pro 16X OLED" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Categoría <span class="text-emerald-400">*</span>
                    </label>
                    <select id="categoria" name="categoria" required class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}" {{ old('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Estado / Disponibilidad -->
                <div class="flex flex-col justify-center">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Estado en Catálogo
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer select-none mt-1">
                        <input type="checkbox" name="estado" value="1" {{ old('estado', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-medium text-slate-300 peer-checked:text-emerald-300">Activo y Visible para Ventas</span>
                    </label>
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Precio Unitario (MXN/USD) <span class="text-emerald-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 font-mono text-sm">$</span>
                        <input type="number" step="0.01" min="0" id="precio" name="precio" value="{{ old('precio') }}" required placeholder="0.00" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl pl-8 pr-4 py-3 text-sm text-slate-100 font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Stock Inicial en Inventario <span class="text-emerald-400">*</span>
                    </label>
                    <input type="number" min="0" step="1" id="stock" name="stock" value="{{ old('stock', 1) }}" required placeholder="1" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Descripción Detallada
                    </label>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Detalla las características clave del producto..." class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">{{ old('descripcion') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Panel 2: Atributos Dinámicos NoSQL -->
        <div class="glass-panel p-6 sm:p-8 rounded-3xl space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-800 pb-3">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-500/10 text-teal-400 flex items-center justify-center text-xs font-mono font-bold">2</span>
                        <span>Especificaciones NoSQL Dinámicas (BSON Embed)</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Agrega propiedades clave-valor personalizadas que se almacenarán dentro del documento MongoDB sin necesidad de alterar esquemas rígidos.
                    </p>
                </div>
                <button type="button" onclick="agregarFilaAtributo()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Agregar Propiedad</span>
                </button>
            </div>

            <!-- Dynamic attributes container -->
            <div id="contenedor-atributos" class="space-y-3 pt-2">
                <!-- Row 1 Default -->
                <div class="flex items-center gap-3 fila-atributo">
                    <div class="flex-1">
                        <input type="text" name="attr_claves[]" placeholder="Propiedad (ej: procesador, ram, marca)" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition font-mono">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="attr_valores[]" placeholder="Valor (ej: Intel Core i7, 16 GB DDR5)" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition">
                    </div>
                    <button type="button" onclick="eliminarFilaAtributo(this)" class="p-2.5 rounded-xl bg-slate-800 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-slate-700 transition" title="Eliminar fila">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Submit & Cancel Actions -->
        <div class="flex items-center justify-end space-x-4 pt-2">
            <a href="{{ route('productos.index') }}" class="px-5 py-3 rounded-xl text-sm font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                Cancelar
            </a>
            <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 rounded-xl text-sm font-semibold text-slate-950 bg-gradient-to-r from-emerald-400 to-teal-300 hover:from-emerald-300 hover:to-teal-200 shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Guardar en MongoDB</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function agregarFilaAtributo() {
        const contenedor = document.getElementById('contenedor-atributos');
        const fila = document.createElement('div');
        fila.className = 'flex items-center gap-3 fila-atributo animate-fade-in';
        fila.innerHTML = `
            <div class="flex-1">
                <input type="text" name="attr_claves[]" placeholder="Propiedad (ej: color, garantia, peso)" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition font-mono">
            </div>
            <div class="flex-1">
                <input type="text" name="attr_valores[]" placeholder="Valor (ej: Negro espacial, 2 años)" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition">
            </div>
            <button type="button" onclick="eliminarFilaAtributo(this)" class="p-2.5 rounded-xl bg-slate-800 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-slate-700 transition" title="Eliminar fila">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        contenedor.appendChild(fila);
    }

    function eliminarFilaAtributo(boton) {
        const fila = boton.closest('.fila-atributo');
        const filasTotales = document.querySelectorAll('.fila-atributo').length;
        if (filasTotales > 1) {
            fila.remove();
        } else {
            // Limpiar inputs si es la última fila
            fila.querySelectorAll('input').forEach(input => input.value = '');
        }
    }
</script>
@endsection
