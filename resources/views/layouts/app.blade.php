<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de Gestión de Productos CRUD con Laravel y MongoDB NoSQL">
    <title>@yield('title', 'Gestión de Productos') | Laravel + MongoDB NoSQL</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        mongo: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                            accent: '#00ED64',
                            dark: '#001E2B',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f17;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.03) 0px, transparent 50%);
            background-attachment: fixed;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.15);
        }
        .badge-glow {
            box-shadow: 0 0 12px -2px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo & Title -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('productos.index') }}" class="group flex items-center space-x-3.5 focus:outline-none">
                        <div class="relative flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 via-teal-500 to-cyan-400 p-0.5 shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                                <!-- MongoDB leaf SVG icon -->
                                <svg class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.002 0c-.27 0-.54.08-.77.25C9.722 1.4 5 5.56 5 12.06c0 4.14 2.1 7.74 5.37 9.87.52.34 1.13.56 1.63 2.07.5-1.51 1.11-1.73 1.63-2.07 3.27-2.13 5.37-5.73 5.37-9.87 0-6.5-4.722-10.66-6.23-11.81a1.44 1.44 0 0 0-.768-.25zm.02 2.22c.98 1.05 4.96 5.61 4.96 9.84 0 3.73-1.89 6.94-4.75 8.78-.13-.53-.33-1.07-.63-1.57-.49-.83-1.12-1.52-1.71-2.18-.75-.85-1.42-1.77-1.42-3.09 0-1.88 1.4-3.69 2.55-5.28.42-.58.78-1.16 1-1.76v-.01c0-.01-.01-.01 0 0 .15-.71.12-1.5-.16-2.23.51-.83.99-1.67 1.16-2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">
                                    Product<span class="text-emerald-400">Mongo</span>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    Laravel 13 + NoSQL
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-normal">Panel de Administración de Colecciones</p>
                        </div>
                    </a>
                </div>

                <!-- Database status indicator -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="flex items-center space-x-2.5 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-slate-400">Motor: <strong class="text-emerald-400 font-mono">MongoDB (27017)</strong></span>
                        <span class="text-slate-600">|</span>
                        <span class="text-slate-400">BD: <span class="text-slate-200 font-mono">mi_base_de_datos</span></span>
                    </div>

                    <!-- Action buttons -->
                    <a href="{{ route('productos.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-semibold text-slate-950 bg-gradient-to-r from-emerald-400 to-teal-300 hover:from-emerald-300 hover:to-teal-200 shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Nuevo Producto</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Flash Message: Success -->
            @if(session('exito'))
                <div id="toast-success" class="mb-6 flex items-center justify-between p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 shadow-xl shadow-emerald-950/20 backdrop-blur-md animate-fade-in">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-emerald-100">{{ session('exito') }}</p>
                            <p class="text-xs text-emerald-400/80 font-mono">Operación completada en colección MongoDB "productos"</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('toast-success').style.display='none'" class="text-emerald-400 hover:text-emerald-200 p-1.5 rounded-lg hover:bg-emerald-500/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Flash Message: Error -->
            @if(session('error'))
                <div id="toast-error" class="mb-6 flex items-center justify-between p-4 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200 shadow-xl shadow-rose-950/20 backdrop-blur-md">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 border border-rose-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-rose-100">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('toast-error').style.display='none'" class="text-rose-400 hover:text-rose-200 p-1.5 rounded-lg hover:bg-rose-500/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Content Slot -->
            @yield('content')

        </div>
    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-slate-800/80 mt-12 py-8 text-center text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Desarrollado para evaluación académica por <strong class="text-slate-200">Marco</strong></span>
            </div>
            <div class="flex items-center space-x-4 text-slate-500">
                <span>Laravel Framework 13</span>
                <span>•</span>
                <span>MongoDB Eloquent 5.11</span>
                <span>•</span>
                <span>PHP 8.3 NoSQL Engine</span>
            </div>
            <div>
                <a href="{{ route('productos.index') }}" class="text-emerald-400 hover:underline">Inicio CRUD</a>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
