<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — EduColegio</title>
    <meta name="description" content="Portal de acceso al Sistema de Gestión de Aprendizaje EduColegio">
    @include('layouts.partials._styles')
</head>
<body class="h-full min-h-screen bg-slate-950 font-sans antialiased flex items-center justify-center relative overflow-hidden">

    {{-- Fondo con gradientes animados --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-sky-500/12 rounded-full blur-3xl animate-pulse [animation-delay:2s]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-violet-500/8 rounded-full blur-3xl animate-pulse [animation-delay:4s]"></div>
    </div>

    {{-- Card de Login --}}
    <div class="relative z-10 w-full max-w-md mx-4">
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-10 shadow-2xl shadow-black/50"
             style="animation: slideUp .5s cubic-bezier(0.34,1.56,0.64,1) both;">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-sky-500 text-3xl mb-4 shadow-xl shadow-indigo-500/40"
                     style="animation: pulse 2.5s ease-in-out infinite;">
                    🎓
                </div>
                <h1 class="text-2xl font-extrabold bg-gradient-to-r from-slate-100 to-indigo-400 bg-clip-text text-transparent">
                    EduColegio
                </h1>
                <p class="text-sm text-slate-500 mt-1">Sistema de Gestión de Aprendizaje</p>
            </div>

            {{-- Errores --}}
            @if($errors->any())
                <div class="alert alert-error mb-6">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="tu@correo.com"
                               class="input pl-10 {{ $errors->has('email') ? 'input-error' : '' }}"
                               required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••"
                               class="input pl-10 {{ $errors->has('password') ? 'input-error' : '' }}"
                               required>
                    </div>
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" id="remember" name="remember" value="1"
                           class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-indigo-500 focus:ring-indigo-500/30 cursor-pointer accent-indigo-500">
                    <label for="remember" class="text-sm text-slate-400 cursor-pointer">
                        Mantener sesión iniciada
                    </label>
                </div>

                {{-- Botón --}}
                <button type="submit" id="loginBtn"
                        class="w-full btn btn-primary justify-center py-3 text-base shadow-xl shadow-indigo-500/30">
                    Iniciar Sesión
                </button>
            </form>

            <p class="text-center text-xs text-slate-600 mt-6">
                El acceso es solo por invitación del administrador.
            </p>
        </div>
    </div>

    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,.4); }
            50%       { box-shadow: 0 0 0 12px rgba(99,102,241,0); }
        }
    </style>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.textContent = 'Verificando...';
            btn.disabled = true;
            btn.classList.add('opacity-70');
        });
    </script>
</body>
</html>
