@extends('layouts.docente')

@section('title', 'Subir Archivo')
@section('page-title', 'Subir Archivo')
@section('breadcrumb', 'Docente / Archivos / Subir')

@section('content')

<div class="max-w-xl">
    <form method="POST" action="{{ route('docente.archivos.store') }}"
          enctype="multipart/form-data" id="uploadForm">
        @csrf

        <div class="card mb-5">
            <div class="card-header">
                <span class="card-title">📤 Datos del Archivo</span>
            </div>
            <div class="card-body space-y-5">

                {{-- Selector de asignación --}}
                <div class="form-group mb-0">
                    <label class="form-label" for="asignacion_id">Asignación *</label>
                    <p class="text-xs text-slate-500 mb-2">
                        Selecciona el curso, grado y sección al que pertenece este archivo.
                    </p>
                    @if($asignaciones->isEmpty())
                        <div class="p-4 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm">
                            ⚠️ No tienes asignaciones registradas. Contacta al administrador.
                        </div>
                    @else
                        <select id="asignacion_id" name="asignacion_id"
                                class="input {{ $errors->has('asignacion_id') ? 'input-error' : '' }}" required>
                            <option value="">— Seleccionar asignación —</option>
                            @foreach($asignaciones as $asig)
                                <option value="{{ $asig->id }}"
                                        {{ old('asignacion_id') == $asig->id ? 'selected' : '' }}>
                                    {{ $asig->curso->nombre }}
                                    · {{ $asig->grado->nivel->nombre }} {{ $asig->grado->nombre }}
                                    · Sección {{ $asig->seccion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('asignacion_id')
                            <p class="error-msg">⚠ {{ $message }}</p>
                        @enderror
                    @endif
                </div>

                {{-- Zona de carga de archivo --}}
                <div class="form-group mb-0">
                    <label class="form-label" for="archivo">Archivo *</label>
                    <div id="dropZone"
                         class="relative border-2 border-dashed border-slate-600 rounded-xl p-8 text-center
                                transition-all duration-200 cursor-pointer
                                hover:border-emerald-500 hover:bg-emerald-500/5"
                         onclick="document.getElementById('archivo').click()">

                        <div id="dropContent">
                            <div class="text-4xl mb-3">📁</div>
                            <p class="text-sm font-medium text-slate-300">
                                Arrastra tu archivo aquí o
                                <span class="text-emerald-400 underline">haz clic para seleccionar</span>
                            </p>
                            <p class="text-xs text-slate-500 mt-2">
                                PDF, Word, Excel, PowerPoint · Máx. <strong class="text-slate-400">50 MB</strong>
                            </p>
                        </div>

                        {{-- Preview del archivo seleccionado --}}
                        <div id="filePreview" class="hidden">
                            <div class="text-4xl mb-2" id="fileIcon">📄</div>
                            <p class="text-sm font-semibold text-slate-100" id="fileName"></p>
                            <p class="text-xs text-slate-500 mt-1" id="fileSize"></p>
                            <button type="button"
                                    onclick="event.stopPropagation(); clearFile()"
                                    class="mt-3 text-xs text-red-400 hover:text-red-300 underline">
                                Quitar archivo
                            </button>
                        </div>

                        <input type="file" id="archivo" name="archivo"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                               class="hidden"
                               onchange="handleFileSelect(this)">
                    </div>
                    @error('archivo')
                        <p class="error-msg">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción opcional --}}
                <div class="form-group mb-0">
                    <label class="form-label" for="descripcion">
                        Descripción
                        <span class="text-slate-600 normal-case">(opcional)</span>
                    </label>
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="input resize-none {{ $errors->has('descripcion') ? 'input-error' : '' }}"
                              placeholder="Breve descripción del contenido del archivo...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="error-msg">⚠ {{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Barra de progreso (visible durante la subida) --}}
        <div id="progressBar" class="hidden card mb-5 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-400">Subiendo archivo...</span>
                <span class="text-xs text-emerald-400" id="progressPct">0%</span>
            </div>
            <div class="w-full bg-slate-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-300"
                     id="progressFill" style="width:0%"></div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" id="submitBtn" class="btn btn-success"
                    {{ $asignaciones->isEmpty() ? 'disabled' : '' }}>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Subir Archivo
            </button>
            <a href="{{ route('docente.archivos.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>

    </form>
</div>

@endsection

@section('scripts')
<script>
    const MAX_SIZE_BYTES = 50 * 1024 * 1024; // 50 MB

    const FILE_ICONS = {
        'pdf': '📄', 'doc': '📝', 'docx': '📝',
        'xls': '📊', 'xlsx': '📊',
        'ppt': '📑', 'pptx': '📑',
    };

    function handleFileSelect(input) {
        const file = input.files[0];
        if (!file) return;

        // Validación de tamaño en frontend (50 MB)
        if (file.size > MAX_SIZE_BYTES) {
            alert('El archivo supera el límite de 50 MB. Por favor selecciona un archivo más pequeño.');
            clearFile();
            return;
        }

        const ext = file.name.split('.').pop().toLowerCase();
        const icon = FILE_ICONS[ext] || '📎';
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        const sizeText = file.size >= 1048576
            ? `${sizeMB} MB`
            : `${(file.size / 1024).toFixed(1)} KB`;

        document.getElementById('fileIcon').textContent = icon;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = sizeText;

        document.getElementById('dropContent').classList.add('hidden');
        document.getElementById('filePreview').classList.remove('hidden');
        document.getElementById('dropZone').classList.add('border-emerald-500', 'bg-emerald-500/5');
    }

    function clearFile() {
        document.getElementById('archivo').value = '';
        document.getElementById('dropContent').classList.remove('hidden');
        document.getElementById('filePreview').classList.add('hidden');
        document.getElementById('dropZone').classList.remove('border-emerald-500', 'bg-emerald-500/5');
    }

    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-emerald-500', 'scale-[1.01]');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-emerald-500', 'scale-[1.01]');
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-emerald-500', 'scale-[1.01]');
        const file = e.dataTransfer.files[0];
        if (file) {
            const input = document.getElementById('archivo');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            handleFileSelect(input);
        }
    });

    // Feedback visual al enviar
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('archivo');
        if (!fileInput.files.length) return;

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Subiendo...';

        // Simular barra de progreso (la real requeriría XHR)
        document.getElementById('progressBar').classList.remove('hidden');
        let pct = 0;
        const interval = setInterval(() => {
            pct = Math.min(pct + Math.random() * 15, 90);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('progressPct').textContent = Math.round(pct) + '%';
        }, 200);
    });
</script>
@endsection
