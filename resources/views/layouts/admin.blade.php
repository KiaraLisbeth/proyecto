<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — I.E.P Esther Carson — Admin</title>
    <meta name="description" content="Panel de Administración - Sistema I.E.P Esther Carson">
    @include('layouts.partials._styles')
    {{-- Dependencias para previsualizar archivos Word (.docx) --}}
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.3/dist/docx-preview.min.js"></script>
</head>
<body class="h-full bg-blue-50 dark:bg-slate-950 text-gray-800 dark:text-slate-100 font-sans antialiased transition-colors duration-300">

    <div class="flex h-full min-h-screen">

        {{-- ── Sidebar (desktop fijo, mobile overlay) ──────────────── --}}
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-blue-900 border-r border-blue-700/50
                      transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

            {{-- Brand --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-blue-700/50 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 shadow-lg shadow-indigo-500/30">
                    <img src="{{ asset($institucionGlobal['logo'] ?? 'images/logo_iep.jpg') }}" alt="Logo I.E.P Esther Carson" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="text-sm font-bold bg-gradient-to-r from-sky-400 to-blue-300 bg-clip-text text-transparent">
                        I.E.P Esther Carson
                    </div>
                    <div class="text-xs text-slate-500">Panel Administrador</div>
                </div>
                {{-- Botón cerrar sidebar en mobile --}}
                <button id="closeSidebar" class="ml-auto md:hidden text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-widest">Principal</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-widest">Gestión</p>

                <a href="{{ route('admin.docentes.index') }}"
                   class="nav-link {{ request()->routeIs('admin.docentes.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Docentes
                </a>

                <a href="{{ route('admin.archivos.index') }}"
                   class="nav-link {{ request()->routeIs('admin.archivos.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Documentos
                </a>

                <a href="{{ route('admin.reportes.index') }}"
                   class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Reportes
                </a>



                <p class="px-3 pt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-widest">Historial</p>

                <a href="{{ route('admin.anios.index') }}"
                   class="nav-link {{ request()->routeIs('admin.anios.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Año Lectivo
                    @php $anioActivo = \App\Models\AnioLectivo::actual(); @endphp
                    @if($anioActivo)
                        <span class="ml-auto text-xs bg-blue-500/20 text-blue-300 rounded px-1.5 font-semibold">
                            {{ $anioActivo->anio }}
                        </span>
                    @endif
                </a>

            </nav>

            {{-- User info footer --}}
            <div class="border-t border-slate-700/50 p-4 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold truncate">{{ auth()->user()->nombre_completo }}</div>
                        <div class="text-xs text-sky-400">Administrador</div>
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

        {{-- Overlay para mobile --}}
        <div id="sidebarOverlay"
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm hidden md:hidden"
             onclick="toggleSidebar()"></div>

        {{-- ── Main Content ──────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-h-screen md:ml-64">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-b border-gray-200 dark:border-slate-700/50 transition-colors duration-300">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        {{-- Botón hamburguesa mobile --}}
                        <button id="openSidebar" class="md:hidden text-slate-400 hover:text-slate-100 transition-colors"
                                onclick="toggleSidebar()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-base font-bold text-gray-800 dark:text-slate-100">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">@yield('breadcrumb', 'Admin')</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Año Lectivo Activo --}}
                        @php $anioActivo = \App\Models\AnioLectivo::actual(); @endphp
                        @if($anioActivo)
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                                     bg-blue-50 dark:bg-blue-500/10
                                     text-blue-700 dark:text-blue-400
                                     border border-blue-200 dark:border-blue-500/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $anioActivo->anio }}
                        </span>
                        @endif

                        {{-- Botón Theme Toggle --}}
                        <button id="themeToggle" class="p-2 mr-2 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-colors focus:outline-none" title="Cambiar tema">
                            {{-- Icono Luna (Claro) --}}
                            <svg id="themeIconMoon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{-- Icono Sol (Oscuro) --}}
                            <svg id="themeIconSun" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        @yield('topbar-actions')
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success') || session('error') || session('warning') || $errors->any())
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
                    @if($errors->any())
                        <div class="alert alert-error mb-3" id="flashMsg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <span class="font-bold block">Por favor corrige los siguientes errores:</span>
                                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
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

    {{-- ── Modal de confirmación (reutilizable) ───────────────────── --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-100">Confirmar acción</h3>
            </div>
            <p class="text-sm text-slate-400 mb-6 leading-relaxed" id="deleteModalText">
                ¿Estás seguro? Esta acción no se puede deshacer.
            </p>
            <div class="flex gap-3 justify-end">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeModal()">Cancelar</button>
                <form id="deleteModalForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Sí, confirmar</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal de Previsualización de Archivos ────────────────── --}}
    <div class="modal-overlay" id="previewModal">
        <div class="modal-box !max-w-4xl !w-11/12 !p-0 overflow-hidden flex flex-col h-[85vh]">
            {{-- Header del Modal --}}
            <div class="px-5 py-4 border-b border-gray-200 dark:border-slate-700/50 flex items-center justify-between bg-gray-50 dark:bg-slate-800/50">
                <div class="flex items-center gap-3 min-w-0">
                    <div id="previewIcon" class="w-8 h-8 rounded-lg bg-sky-100 text-sky-600 dark:bg-slate-700/50 dark:text-sky-400 flex items-center justify-center flex-shrink-0 text-xl">
                        📄
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-slate-100 truncate" id="previewTitle">
                        Previsualizando Archivo
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Botón Imprimir --}}
                    <button type="button" id="printBtn" class="text-gray-500 hover:text-sky-600 dark:text-slate-400 dark:hover:text-sky-400 transition-colors p-1 flex items-center gap-1 text-xs font-bold" onclick="printPreviewFile()" title="Imprimir directamente">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Imprimir</span>
                    </button>
                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors p-1" onclick="closePreviewModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- Contenido del Modal --}}
            <div class="flex-1 bg-gray-100/50 dark:bg-slate-900/50 relative flex items-center justify-center p-4">
                {{-- Spinner de Carga --}}
                <div id="previewLoading" class="absolute inset-0 flex flex-col items-center justify-center z-10 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-sky-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-slate-400">Cargando previsualización...</p>
                </div>

                {{-- Iframe para PDFs --}}
                <iframe id="previewIframe" class="w-full h-full border-0 rounded-xl shadow-sm bg-white hidden" src=""></iframe>
                
                {{-- Imagen --}}
                <img id="previewImage" class="max-w-full max-h-full object-contain rounded-xl shadow-sm hidden" src="" alt="Previsualización">

                {{-- Contenedor Word (.docx) --}}
                <div id="previewDocx" class="w-full h-full overflow-auto bg-white rounded-xl shadow-sm hidden p-6 text-gray-800" style="font-family: 'Calibri', sans-serif;"></div>

                {{-- Mensaje para archivos no soportados --}}
                <div id="previewUnsupported" class="text-center max-w-xs hidden">
                    <div id="previewUnsupportedIcon" class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-500/10 dark:to-orange-500/10 flex items-center justify-center mx-auto mb-5 text-4xl shadow-sm">
                        📄
                    </div>
                    <h4 id="previewUnsupportedTitle" class="text-base font-bold text-gray-800 dark:text-slate-100 mb-2">
                        Vista previa no disponible
                    </h4>
                    <p id="previewUnsupportedMsg" class="text-sm text-gray-500 dark:text-slate-400 mb-6 leading-relaxed">
                        Este formato no se puede previsualizar en el navegador.
                    </p>
                    <a href="#" id="previewDownloadBtn"
                       class="btn btn-primary justify-center w-full gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar Archivo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Sidebar mobile toggle ──────────────────────────────────────
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        document.getElementById('closeSidebar')?.addEventListener('click', toggleSidebar);

        // ── Modal de confirmación ────────────────────────────────────
        function openModal(action, message) {
            document.getElementById('deleteModalText').textContent = message || '¿Estás seguro? Esta acción no se puede deshacer.';
            document.getElementById('deleteModalForm').action = action;
            document.getElementById('deleteModal').style.setProperty('display', 'flex', 'important');
        }
        function closeModal() {
            document.getElementById('deleteModal').style.setProperty('display', 'none', 'important');
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ── Modal de Previsualización ———————————————————————
        function openPreviewModal(url, type, name, downloadUrl, signedUrlEndpoint) {
            const modal        = document.getElementById('previewModal');
            const title        = document.getElementById('previewTitle');
            const iframe       = document.getElementById('previewIframe');
            const img          = document.getElementById('previewImage');
            const docxBox      = document.getElementById('previewDocx');
            const unsupported  = document.getElementById('previewUnsupported');
            const downloadBtn  = document.getElementById('previewDownloadBtn');
            const loading      = document.getElementById('previewLoading');
            const printBtn     = document.getElementById('printBtn');

            title.textContent = name;

            // Reiniciar todas las vistas
            iframe.classList.add('hidden');      iframe.src = '';
            img.classList.add('hidden');         img.src = '';
            docxBox.classList.add('hidden');     docxBox.innerHTML = '';
            unsupported.classList.add('hidden');
            loading.classList.remove('hidden');
            if (printBtn) printBtn.classList.remove('hidden');

            // Usar data-ext (type) para detección confiable; fallback al nombre
            const ext = (type && type.trim().length > 0)
                ? type.toLowerCase()
                : name.split('.').pop().toLowerCase();

            if (ext === 'pdf') {
                iframe.src = url;
                iframe.onload = () => loading.classList.add('hidden');
                iframe.classList.remove('hidden');

            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
                img.src = url;
                img.onload = () => loading.classList.add('hidden');
                img.classList.remove('hidden');

            } else if (['docx', 'doc'].includes(ext)) {
                // Verificar que la librería docx-preview esté disponible
                const docxLib = window.docx || (typeof docx !== 'undefined' ? docx : null);
                if (!docxLib || typeof docxLib.renderAsync !== 'function') {
                    console.error('docx-preview no disponible. Asegúrate de tener conexión a internet para cargar la librería.');
                    loading.classList.add('hidden');
                    unsupported.classList.remove('hidden');
                    if (printBtn) printBtn.classList.add('hidden');
                    downloadBtn.href = downloadUrl;
                } else {
                    fetch(url, { credentials: 'same-origin' })
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP ' + res.status + ': Error al cargar el archivo');
                            return res.arrayBuffer();
                        })
                        .then(buffer => {
                            docxBox.innerHTML = '';
                            docxBox.classList.remove('hidden');
                            return docxLib.renderAsync(buffer, docxBox, null, {
                                className: 'docx-render',
                                inWrapper: true,
                                ignoreWidth: false,
                                ignoreHeight: true,
                                ignoreFonts: false,
                                breakPages: true,
                                useBase64URL: true,
                            });
                        })
                        .then(() => loading.classList.add('hidden'))
                        .catch(err => {
                            console.error('Error renderizando DOCX:', err);
                            loading.classList.add('hidden');
                            docxBox.classList.add('hidden');
                            unsupported.classList.remove('hidden');
                            if (printBtn) printBtn.classList.add('hidden');
                            downloadBtn.href = downloadUrl;
                        });
                }

            } else if (['pptx', 'ppt'].includes(ext)) {
                // PowerPoint: no hay visor pure-JS confiable en local
                loading.classList.add('hidden');
                if (printBtn) printBtn.classList.add('hidden');
                document.getElementById('previewUnsupportedIcon').textContent = '📑';
                document.getElementById('previewUnsupportedTitle').textContent = 'PowerPoint (.pptx/.ppt)';
                document.getElementById('previewUnsupportedMsg').textContent =
                    'Los archivos PowerPoint no se pueden previsualizar directamente en el navegador. Descárgalo para abrirlo con PowerPoint o LibreOffice.';
                downloadBtn.href = downloadUrl;
                unsupported.classList.remove('hidden');

            } else if (['xlsx', 'xls', 'ods'].includes(ext)) {
                // Excel: sin visor inline
                loading.classList.add('hidden');
                if (printBtn) printBtn.classList.add('hidden');
                document.getElementById('previewUnsupportedIcon').textContent = '📊';
                document.getElementById('previewUnsupportedTitle').textContent = 'Hoja de cálculo (.xlsx/.xls)';
                document.getElementById('previewUnsupportedMsg').textContent =
                    'Las hojas de cálculo no se pueden previsualizar en el navegador. Descárgala para abrirla con Excel o LibreOffice Calc.';
                downloadBtn.href = downloadUrl;
                unsupported.classList.remove('hidden');

            } else {
                loading.classList.add('hidden');
                if (printBtn) printBtn.classList.add('hidden');
                document.getElementById('previewUnsupportedIcon').textContent = '📎';
                document.getElementById('previewUnsupportedTitle').textContent = 'Formato no compatible';
                document.getElementById('previewUnsupportedMsg').textContent =
                    'Este tipo de archivo no se puede previsualizar en el navegador. Descárgalo para verlo.';
                downloadBtn.href = downloadUrl;
                unsupported.classList.remove('hidden');
            }

            modal.style.setProperty('display', 'flex', 'important');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').style.setProperty('display', 'none', 'important');
            document.getElementById('previewIframe').src = '';
            document.getElementById('previewImage').src = '';
            document.getElementById('previewDocx').innerHTML = '';
        }

        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) closePreviewModal();
        });

        function printPreviewFile() {
            const iframe      = document.getElementById('previewIframe');
            const img          = document.getElementById('previewImage');
            const docxBox      = document.getElementById('previewDocx');

            if (!iframe.classList.contains('hidden') && iframe.src) {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (e) {
                    window.open(iframe.src, '_blank').print();
                }
            } else if (!img.classList.contains('hidden') && img.src) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Imprimir Imagen</title></head><body style="margin:0; display:flex; align-items:center; justify-content:center; height:100vh;"><img src="' + img.src + '" style="max-width:100%; max-height:100%; object-fit:contain;"/></body></html>');
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            } else if (!docxBox.classList.contains('hidden')) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Imprimir Documento</title><style>body { font-family: Calibri, sans-serif; padding: 40px; line-height: 1.5; } .docx-render { margin: 0 auto; }</style></head><body>' + docxBox.innerHTML + '</body></html>');
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            } else {
                alert('La impresión directa no está disponible para este tipo de archivo.');
            }
        }

        // ── Auto-cerrar flash messages ────────────────────────────────
        document.querySelectorAll('#flashMsg').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity .4s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 4500);
        });

        // ── Prevenir doble click al enviar formularios ────────────────
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.getAttribute('target') === '_blank') return;
            
            const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            
            setTimeout(() => {
                if (e.defaultPrevented) return;
                submitBtns.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');
                });
            }, 0);
        });
    </script>

    @yield('scripts')
</body>
</html>
