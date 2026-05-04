<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — EduColegio Docente</title>
    <meta name="description" content="Portal Docente - Sistema EduColegio">
    @include('layouts.partials._styles')
</head>
<body class="h-full bg-slate-950 text-slate-100 font-sans antialiased">

    <div class="flex h-full min-h-screen">

        {{-- ── Sidebar Docente ──────────────────────────────────────── --}}
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-slate-900 border-r border-slate-700/50
                      transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

            {{-- Brand --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700/50 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-lg shadow-lg shadow-emerald-500/30 flex-shrink-0">
                    👩‍🏫
                </div>
                <div>
                    <div class="text-sm font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">
                        EduColegio
                    </div>
                    <div class="text-xs text-slate-500">Portal Docente</div>
                </div>
                <button id="closeSidebar" class="ml-auto md:hidden text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-widest">Mi Portal</p>

                <a href="{{ route('docente.dashboard') }}"
                   class="nav-link {{ request()->routeIs('docente.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Mi Dashboard
                </a>

                <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-widest">Archivos</p>

                <a href="{{ route('docente.archivos.create') }}"
                   class="nav-link {{ request()->routeIs('docente.archivos.create') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Subir Archivo
                </a>

                <a href="{{ route('docente.archivos.index') }}"
                   class="nav-link {{ request()->routeIs('docente.archivos.index') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Mis Documentos
                </a>
            </nav>

            {{-- User info footer --}}
            <div class="border-t border-slate-700/50 p-4 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold truncate">{{ auth()->user()->nombre_completo }}</div>
                        <div class="text-xs text-emerald-400">Docente</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Cerrar sesión"
                                class="text-slate-500 hover:text-red-400 transition-colors p-1 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div id="sidebarOverlay"
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden md:hidden"
             onclick="toggleSidebar()"></div>

        {{-- ── Main Content ──────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-h-screen md:ml-64">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 bg-slate-900/95 backdrop-blur border-b border-slate-700/50">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <button class="md:hidden text-slate-400 hover:text-slate-100 transition-colors"
                                onclick="toggleSidebar()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-base font-bold text-slate-100">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-xs text-slate-500 mt-0.5">@yield('breadcrumb', 'Docente')</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @yield('topbar-actions')
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success') || session('error') || session('warning'))
                <div class="px-6 pt-5">
                    @if(session('success'))
                        <div class="alert alert-success" id="flashMsg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-error" id="flashMsg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning" id="flashMsg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Modal de confirmación --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-100">Confirmar eliminación</h3>
            </div>
            <p class="text-sm text-slate-400 mb-6 leading-relaxed" id="deleteModalText">
                ¿Estás seguro? El archivo será eliminado permanentemente del servidor.
            </p>
            <div class="flex gap-3 justify-end">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeModal()">Cancelar</button>
                <form id="deleteModalForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        document.getElementById('closeSidebar')?.addEventListener('click', toggleSidebar);

        function openModal(action, message) {
            document.getElementById('deleteModalText').textContent = message || '¿Estás seguro?';
            document.getElementById('deleteModalForm').action = action;
            document.getElementById('deleteModal').classList.add('show');
        }
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.querySelectorAll('#flashMsg').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity .4s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 4500);
        });
    </script>

    @yield('scripts')
</body>
</html>
