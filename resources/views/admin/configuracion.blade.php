@extends('layouts.admin')

@section('title', 'Configuración')
@section('page-title', 'Configuración')
@section('breadcrumb', 'Admin / Configuración')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ══════════════════════════════════════════════════════════════
         COLUMNA 1 — Perfil + Seguridad
    ══════════════════════════════════════════════════════════════ --}}
    <div class="space-y-5">

        {{-- Perfil --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Perfil del Administrador</span>
            </div>
            <div class="p-5">
                {{-- Info de solo lectura --}}
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600
                                flex items-center justify-center text-2xl font-black text-white shadow flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-gray-900 dark:text-white">{{ auth()->user()->nombre_completo }}</p>
                        <p class="text-xs font-mono font-bold text-gray-400 dark:text-slate-500 mt-0.5">👤 {{ auth()->user()->username }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                         bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                Administrador
                            </span>
                            <span class="text-[10px] text-gray-300 dark:text-slate-600">
                                Desde {{ auth()->user()->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seguridad / Cambiar contraseña --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Cambiar Contraseña</span>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.perfil.password') }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Contraseña actual</label>
                            <input type="password" name="password_actual" autocomplete="current-password"
                                   class="input w-full @error('password_actual') border-red-400 @enderror">
                            @error('password_actual') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Nueva contraseña</label>
                            <input type="password" name="password" autocomplete="new-password"
                                   class="input w-full @error('password') border-red-400 @enderror">
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                   class="input w-full">
                        </div>
                        <button type="submit" class="btn btn-sm w-full mt-1
                                bg-amber-500 hover:bg-amber-600 text-white border-0">
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         COLUMNA 2 — Información de la Institución
    ══════════════════════════════════════════════════════════════ --}}
    <div class="space-y-5">

        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Información de la Institución</span>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.perfil.institucion') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Nombre de la institución</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $institucion['nombre'] ?? 'I.E.P Esther Carson') }}"
                                   class="input w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $institucion['direccion'] ?? '') }}"
                                   placeholder="Av. Principal 123" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3">Logo de la Institución</label>
                            <div class="flex items-center gap-5">
                                {{-- Logo actual en círculo --}}
                                <div class="relative group w-20 h-20 rounded-full overflow-hidden border-2 border-gray-100 dark:border-slate-700 bg-white shadow-sm flex-shrink-0 flex items-center justify-center">
                                    <img id="logo-preview" src="{{ asset($institucionGlobal['logo'] ?? 'images/logo_iep.jpg') }}" alt="Logo" class="w-14 h-14 object-contain">
                                </div>
                                
                                {{-- Botón de subida (oculta el input real) --}}
                                <div class="flex-1">
                                    <label class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg cursor-pointer transition-colors dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20 dark:hover:bg-blue-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Cambiar Logo
                                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewLogo(this)">
                                    </label>
                                    <p id="logo-filename" class="text-[11px] text-gray-400 mt-2">Ningún archivo seleccionado (Solo JPG, PNG)</p>
                                </div>
                            </div>
                        </div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        document.getElementById('logo-filename').textContent = input.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $institucion['telefono'] ?? '') }}"
                                       placeholder="987 654 321" class="input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Correo</label>
                                <input type="email" name="email" value="{{ old('email', $institucion['email'] ?? '') }}"
                                       placeholder="info@iep.edu.pe" class="input w-full">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Sitio web</label>
                            <input type="text" name="sitio_web" value="{{ old('sitio_web', $institucion['sitio_web'] ?? '') }}"
                                   placeholder="https://www.iep.edu.pe" class="input w-full">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-full mt-1">
                            Guardar Información
                        </button>
                    </div>
                </form>
            </div>

            {{-- Vista previa de los datos guardados --}}
            @if(!empty($institucion['direccion']) || !empty($institucion['telefono']))
            <div class="px-5 pb-5">
                <div class="rounded-xl bg-gray-50 dark:bg-slate-700/30 p-4 space-y-2 text-xs text-gray-500 dark:text-slate-400">
                    <p class="font-bold text-gray-700 dark:text-slate-300 text-sm mb-2">{{ $institucion['nombre'] ?? '' }}</p>
                    @if(!empty($institucion['direccion']))
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $institucion['direccion'] }}
                    </div>
                    @endif
                    @if(!empty($institucion['telefono']))
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $institucion['telefono'] }}
                    </div>
                    @endif
                    @if(!empty($institucion['email']))
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $institucion['email'] }}
                    </div>
                    @endif
                    @if(!empty($institucion['sitio_web']))
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <a href="{{ $institucion['sitio_web'] }}" target="_blank"
                           class="text-blue-500 hover:underline">{{ $institucion['sitio_web'] }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Configuración Académica (solo lectura) --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Configuración Académica</span>
                <a href="{{ route('admin.anios.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700 dark:hover:text-blue-300">
                    Gestionar →
                </a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-slate-700/30">
                @php
                    $anioActivo = \App\Models\AnioLectivo::actual();
                    $niveles    = \App\Models\Nivel::withCount('grados')->get();
                    $totalGrados = $niveles->sum('grados_count');
                @endphp

                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-slate-300">Año Lectivo Activo</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500">Período académico en curso</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-blue-600 dark:text-blue-400">{{ $anioActivo?->anio ?? '—' }}</span>
                </div>

                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-slate-300">Bimestres</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500">I · II · III · IV</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-violet-600 dark:text-violet-400">4</span>
                </div>

                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-slate-300">Niveles Educativos</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500">{{ $niveles->pluck('nombre')->join(' · ') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $niveles->count() }}</span>
                </div>

                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-slate-300">Grados en total</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500">Distribuidos por nivel</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-amber-600 dark:text-amber-400">{{ $totalGrados }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         COLUMNA 3 — Sistema
    ══════════════════════════════════════════════════════════════ --}}
    <div class="space-y-5">

        {{-- Información del sistema --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Información del Sistema</span>
            </div>
            @php
                $fmtSize = fn($b) => $b >= 1073741824
                    ? round($b/1073741824,1).' GB'
                    : round($b/1048576,1).' MB';
                $diskUsed = disk_total_space(storage_path()) - disk_free_space(storage_path());
            @endphp
            <div class="divide-y divide-gray-50 dark:divide-slate-700/30">
                @foreach([
                    ['label'=>'Versión del Sistema', 'value'=>'v1.0.0',                           'color'=>'text-blue-600 dark:text-blue-400'],
                    ['label'=>'Framework',            'value'=>'Laravel '.app()->version(),         'color'=>'text-red-500 dark:text-red-400'],
                    ['label'=>'PHP',                  'value'=>'v'.PHP_VERSION,                    'color'=>'text-violet-600 dark:text-violet-400'],
                    ['label'=>'Servidor',             'value'=>$_SERVER['SERVER_SOFTWARE'] ?? 'N/A','color'=>'text-gray-600 dark:text-slate-400'],
                ] as $item)
                <div class="flex items-center justify-between px-5 py-3">
                    <span class="text-xs text-gray-500 dark:text-slate-400">{{ $item['label'] }}</span>
                    <span class="text-xs font-bold {{ $item['color'] }}">{{ $item['value'] }}</span>
                </div>
                @endforeach

                {{-- Almacenamiento --}}
                <div class="flex items-center justify-between px-5 py-3">
                    <span class="text-xs text-gray-500 dark:text-slate-400">Almacenamiento</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 dark:text-slate-500">{{ $fmtSize($diskUsed) }} en uso</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                     bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                            ∞ Ilimitado
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos rápidos de gestión --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Gestión del Sistema</span>
            </div>
            <div class="p-4 space-y-2">
                @foreach([
                    ['route'=>'admin.docentes.index',   'label'=>'Gestionar Docentes',   'sub'=>'Crear, editar y activar',        'color'=>'blue'],
                    ['route'=>'admin.archivos.index',   'label'=>'Ver Documentos',        'sub'=>'Todos los archivos subidos',     'color'=>'emerald'],
                    ['route'=>'admin.anios.index',      'label'=>'Años Lectivos',         'sub'=>'Crear y activar períodos',       'color'=>'amber'],
                    ['route'=>'admin.archivos.papelera','label'=>'Papelera',              'sub'=>'Restaurar o eliminar archivos',  'color'=>'red'],
                ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                          border border-gray-100 dark:border-slate-700/50
                          hover:border-{{ $item['color'] }}-200 dark:hover:border-{{ $item['color'] }}-500/30
                          hover:bg-{{ $item['color'] }}-50 dark:hover:bg-{{ $item['color'] }}-500/10
                          text-gray-600 dark:text-slate-400
                          hover:text-{{ $item['color'] }}-700 dark:hover:text-{{ $item['color'] }}-400
                          transition-all group">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold">{{ $item['label'] }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-slate-500">{{ $item['sub'] }}</p>
                    </div>
                    <svg class="w-4 h-4 opacity-30 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection
