<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — I.E.P Esther Carson</title>
    <meta name="description" content="Portal de acceso al Sistema de Gestión de Aprendizaje I.E.P Esther Carson">
    @include('layouts.partials._styles')
</head>
<body class="h-full min-h-screen bg-blue-50 font-sans antialiased flex items-center justify-center relative overflow-hidden">

    {{-- Fondo decorativo claro --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 right-0 translate-x-1/3 -translate-y-1/2 w-[500px] h-[500px] bg-blue-100/60 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-10 w-72 h-72 bg-sky-200/40 rounded-full blur-3xl"></div>
        {{-- Puntos decorativos --}}
        <div class="absolute top-20 left-10 w-32 h-32" style="background-image: radial-gradient(#cbd5e1 2px, transparent 2px); background-size: 16px 16px; opacity: 0.5;"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32" style="background-image: radial-gradient(#cbd5e1 2px, transparent 2px); background-size: 16px 16px; opacity: 0.5;"></div>
    </div>

    {{-- Card de Login --}}
    <div class="relative z-10 w-full max-w-md mx-4">
        <div class="bg-blue-700/95 backdrop-blur-xl border border-blue-600/50 rounded-[2rem] p-10 shadow-2xl shadow-blue-900/20"
             style="animation: slideUp .5s cubic-bezier(0.34,1.56,0.64,1) both;">

            {{-- Logo --}}
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl mb-4 bg-white/10 p-2 shadow-inner backdrop-blur-sm"
                     style="animation: pulse 2.5s ease-in-out infinite;">
                    <img src="{{ asset($institucionGlobal['logo'] ?? 'images/logo_iep.jpg') }}" alt="Logo I.E.P Esther Carson" class="w-full h-full object-contain rounded-xl">
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">
                    I.E.P Esther Carson
                </h1>
                <p class="text-sm text-blue-200">Sistema de Gestión de Aprendizaje</p>
            </div>

            {{-- Divisor --}}
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="h-px w-16 bg-gradient-to-r from-transparent to-blue-400/50"></div>
                <svg class="w-5 h-5 text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2.18-1.19L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                </svg>
                <div class="h-px w-16 bg-gradient-to-l from-transparent to-blue-400/50"></div>
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

                {{-- Usuario --}}
                <div class="form-group mb-5">
                    <label class="flex items-center gap-2 text-xs font-semibold text-white mb-2 tracking-wider" for="username">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        USUARIO
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-blue-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input id="username" type="text" name="username"
                               value="{{ old('username') }}"
                               placeholder="knamuchefl o ADMIN"
                               class="w-full bg-white/95 border-0 rounded-xl pl-10 pr-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all {{ $errors->has('username') ? 'ring-2 ring-red-400' : '' }}"
                               required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group mb-5">
                    <label class="flex items-center gap-2 text-xs font-semibold text-white mb-2 tracking-wider" for="password">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        CONTRASEÑA
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-blue-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••"
                               class="w-full bg-white/95 border-0 rounded-xl pl-10 pr-10 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all {{ $errors->has('password') ? 'ring-2 ring-red-400' : '' }}"
                               required>
                        <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-blue-500 hover:text-blue-700"
                                title="Mostrar/ocultar contraseña">
                            {{-- Ojo abierto --}}
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Ojo cerrado --}}
                            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Recordarme --}}
                <div class="flex items-center gap-2 mb-8">
                    <input type="checkbox" id="remember" name="remember" value="1"
                           class="w-4 h-4 rounded border-0 bg-white/10 text-blue-400 focus:ring-white/30 cursor-pointer">
                    <label for="remember" class="text-sm text-white/90 cursor-pointer select-none">
                        Mantener sesión iniciada
                    </label>
                </div>

                {{-- Botón --}}
                <button type="submit" id="loginBtn"
                        class="w-full bg-blue-500 hover:bg-blue-400 text-white font-semibold flex items-center justify-center gap-2 py-3.5 rounded-xl transition-all shadow-lg shadow-blue-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-8 border-t border-white/10 pt-6 text-center relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-700 px-2 text-white/50">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-xs text-blue-200/70">
                    El acceso es solo por invitación del administrador.
                </p>
            </div>
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
        /* Ocultar el ojo nativo de Edge y navegadores IE/Edge */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>

    <script>
        function togglePassword() {
            const input  = document.getElementById('password');
            const eyeOn  = document.getElementById('eyeOpen');
            const eyeOff = document.getElementById('eyeClosed');
            const visible = input.type === 'text';
            input.type   = visible ? 'password' : 'text';
            eyeOn.classList.toggle('hidden',  !visible);
            eyeOff.classList.toggle('hidden',  visible);
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.textContent = 'Verificando...';
            btn.disabled = true;
            btn.classList.add('opacity-70');
        });
    </script>
</body>
</html>
