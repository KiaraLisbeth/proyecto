{{--
    Partial compartido: Tailwind CSS vía CDN + todos los estilos personalizados.
    Se incluye en layouts/admin.blade.php, layouts/docente.blade.php y auth/login.blade.php.
    No requiere Node.js ni npm.
--}}

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

{{-- Tailwind CSS Play CDN (no requiere build ni npm) --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                },
            }
        }
    }
</script>

{{-- Inicializar tema antes de renderizar para evitar destellos --}}
<script>
    (function() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();

    // ── Toast de notificación de tema ────────────────────────────
    function showThemeToast(isDark) {
        // Eliminar toast anterior si existe
        const old = document.getElementById('themeToast');
        if (old) old.remove();

        const toast = document.createElement('div');
        toast.id = 'themeToast';
        toast.style.cssText = [
            'position:fixed',
            'bottom:24px',
            'right:24px',
            'z-index:9999',
            'display:flex',
            'align-items:center',
            'gap:10px',
            'padding:12px 18px',
            'border-radius:12px',
            'font-family:Inter,sans-serif',
            'font-size:13px',
            'font-weight:600',
            'box-shadow:0 8px 32px rgba(0,0,0,0.25)',
            'backdrop-filter:blur(12px)',
            'border:1px solid',
            'transform:translateY(16px)',
            'opacity:0',
            'transition:all 0.35s cubic-bezier(0.34,1.56,0.64,1)',
            isDark
                ? 'background:rgba(15,23,42,0.92);color:#cbd5e1;border-color:rgba(100,116,139,0.3)'
                : 'background:rgba(255,255,255,0.92);color:#374151;border-color:rgba(209,213,219,0.8)'
        ].join(';');

        toast.innerHTML = isDark
            ? `<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg><span>Modo oscuro activado</span>`
            : `<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#f59e0b;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg><span>Modo claro activado</span>`;

        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
        });
        setTimeout(() => {
            toast.style.transform = 'translateY(8px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 350);
        }, 2200);
    }

    // ── Lógica del botón toggle (espera al DOM) ──────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeSwitch = document.getElementById('themeSwitchKnob');
        const themeTrack = document.getElementById('themeSwitchTrack');

        function applyThemeUI(isDark) {
            // Actualizar switch visual si existe
            if (themeSwitch && themeTrack) {
                if (isDark) {
                    themeTrack.style.background = 'linear-gradient(135deg, #1e293b, #334155)';
                    themeSwitch.style.transform = 'translateX(22px)';
                    themeSwitch.innerHTML = `<svg width="12" height="12" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>`;
                } else {
                    themeTrack.style.background = 'linear-gradient(135deg, #bfdbfe, #93c5fd)';
                    themeSwitch.style.transform = 'translateX(0px)';
                    themeSwitch.innerHTML = `<svg width="12" height="12" fill="none" stroke="#f59e0b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>`;
                }
            }
        }

        // Aplicar UI según tema actual al cargar
        applyThemeUI(document.documentElement.classList.contains('dark'));

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    applyThemeUI(false);
                    showThemeToast(false);
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    applyThemeUI(true);
                    showThemeToast(true);
                }
            });
        }

        // Lógica Global para Botones de Previsualización
        document.querySelectorAll('.btn-preview').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.url;
                const ext = this.dataset.ext;
                const name = this.dataset.name;
                const downloadUrl = this.dataset.download;

                if (typeof openPreviewModal === 'function') {
                    openPreviewModal(url, ext, name, downloadUrl);
                } else {
                    alert('Error: La función openPreviewModal no está definida.');
                }
            });
        });
    });

</script>

{{-- Componentes personalizados del sistema de diseño --}}
<style type="text/tailwindcss">

    /* ── Inputs ─────────────────────────────────────────── */
    .input {
        @apply w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2.5
               text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:border-sky-500
               focus:ring-2 focus:ring-sky-500/20 transition-all duration-200;
    }
    .input-error {
        @apply border-red-500 focus:border-red-500 focus:ring-red-500/20;
    }

    /* ── Sidebar nav ─────────────────────────────────────── */
    .nav-link {
        @apply flex items-center gap-3 px-4 py-3 mx-4 rounded-xl text-sm font-medium
               text-slate-400 hover:text-white hover:bg-white/10 transition-colors;
    }
    .nav-link.active {
        @apply bg-sky-500/10 text-sky-400 font-semibold;
    }
    .nav-link.active::before {
        content: '';
        @apply absolute left-0 w-1 h-8 bg-sky-400 rounded-r-md;
    }

    /* ── Botones ─────────────────────────────────────────── */
    .btn {
        @apply inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
               transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-offset-blue-50 dark:focus:ring-offset-slate-900;
    }
    .btn-primary {
        @apply bg-sky-600 hover:bg-sky-500 text-white shadow-lg shadow-sky-500/25
               hover:-translate-y-px;
    }
    .btn-success {
        @apply bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-500/20
               hover:-translate-y-px;
    }
    .btn-danger {
        @apply bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-500/20
               hover:-translate-y-px;
    }
    .btn-warning {
        @apply bg-amber-500 hover:bg-amber-400 text-white hover:-translate-y-px;
    }
    .btn-ghost {
        @apply bg-slate-700 hover:bg-slate-600 text-slate-200 border border-slate-600;
    }
    .btn-sm  { @apply px-3 py-1.5 text-xs rounded-md; }
    .btn-xs  { @apply px-2 py-1 text-xs rounded; }

    /* ── Cards ───────────────────────────────────────────── */
    .card {
        @apply bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm;
    }
    .card-header {
        @apply px-6 py-4 border-b border-gray-100 dark:border-slate-700/50 flex items-center justify-between;
    }
    .card-title { @apply text-sm font-semibold text-gray-800 dark:text-slate-100; }
    .card-body  { @apply p-6; }

    /* ── Badges ──────────────────────────────────────────── */
    .badge {
        @apply inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold;
    }
    .badge-success { @apply bg-emerald-500/10 text-emerald-400 border border-emerald-500/20; }
    .badge-danger  { @apply bg-red-500/10  text-red-400  border border-red-500/20; }
    .badge-primary { @apply bg-sky-500/10 text-sky-400 border border-sky-500/20; }
    .badge-muted   { @apply bg-slate-700 text-slate-400 border border-slate-600; }
    .badge-warning { @apply bg-amber-500/10 text-amber-400 border border-amber-500/20; }

    /* ── Tabla ───────────────────────────────────────────── */
    .table-wrap { @apply overflow-x-auto; }
    .table      { @apply w-full text-sm; }
    .table thead th {
        @apply bg-gray-50 dark:bg-slate-700/50 px-4 py-3 text-left text-xs font-semibold uppercase
               tracking-wider text-gray-500 dark:text-slate-400 border-b border-gray-200 dark:border-slate-700/50;
    }
    .table tbody tr {
        @apply border-b border-gray-100 dark:border-slate-700/30 transition-colors duration-150;
    }
    .table tbody tr:hover  { @apply bg-sky-50 dark:bg-slate-700/20; }
    .table tbody tr:last-child { @apply border-b-0; }
    .table tbody td { @apply px-4 py-3 align-middle text-gray-700 dark:text-slate-300; }

    /* ── Formulario ──────────────────────────────────────── */
    .form-group { @apply mb-5; }
    .form-label {
        @apply block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide;
    }
    .error-msg { @apply text-xs text-red-500 dark:text-red-400 mt-1.5 flex items-center gap-1; }

    /* ── Stat card ───────────────────────────────────────── */
    .stat-card {
        @apply bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/50 rounded-xl p-6 relative overflow-hidden shadow-sm
               transition-transform duration-200 hover:-translate-y-1;
    }

    /* ── Alertas flash ───────────────────────────────────── */
    .alert {
        @apply flex items-start gap-3 p-4 rounded-lg text-sm border mb-4;
    }
    .alert-success { @apply bg-emerald-500/10 border-emerald-500/30 text-emerald-400; }
    .alert-error   { @apply bg-red-500/10  border-red-500/30  text-red-400; }
    .alert-warning { @apply bg-amber-500/10 border-amber-500/30 text-amber-400; }

    /* ── Colores de íconos por tipo de archivo ───────────── */
    .file-pdf   { color: #f87171; }   /* red-400   */
    .file-word  { color: #60a5fa; }   /* blue-400  */
    .file-excel { color: #34d399; }   /* emerald-400 */
    .file-ppt   { color: #fb923c; }   /* orange-400 */
    .file-other { color: #94a3b8; }   /* slate-400 */

    /* ── Modal ───────────────────────────────────────────── */
    .modal-overlay {
        @apply fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center;
    }
    .modal-overlay.show { display: flex !important; }
    .modal-box {
        @apply bg-gray-100 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-2xl p-7 max-w-md w-11/12 shadow-2xl;
        animation: modalIn .2s cubic-bezier(0.34,1.56,0.64,1);
    }

    /* ── Paginación de Laravel ───────────────────────────── */
    nav[aria-label="pagination"] {
        @apply flex justify-center gap-1 p-4;
    }
    nav[aria-label="pagination"] span,
    nav[aria-label="pagination"] a {
        @apply inline-flex items-center justify-center min-w-9 h-9 px-3 rounded-lg text-xs
               font-medium border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 bg-white dark:bg-slate-800 no-underline
               transition-all duration-150;
    }
    nav[aria-label="pagination"] a:hover {
        @apply bg-sky-600 text-white border-sky-600 dark:border-sky-600;
    }
    nav[aria-label="pagination"] span[aria-current="page"] > span {
        @apply bg-sky-600 text-white border-sky-600 min-w-9 h-9 flex items-center
               justify-center rounded-lg;
    }

    /* ── Scrollbar ───────────────────────────────────────── */
    ::-webkit-scrollbar       { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #475569; }

    /* ── Animaciones ─────────────────────────────────────── */
    @keyframes modalIn {
        from { opacity:0; transform: scale(.95) translateY(10px); }
        to   { opacity:1; transform: scale(1)   translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(-4px); }
        to   { opacity:1; transform:translateY(0); }
    }

</style>
