<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - AnyDesk Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
            -webkit-text-fill-color: #111827;
        }
        .dot-grid {
            background-image: radial-gradient(circle, #ffffff 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-white text-gray-900 antialiased">

    <!-- Painel de marca (visível apenas em telas grandes) -->
    <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between overflow-hidden bg-gray-950 text-white p-12">
        <div class="absolute inset-0 dot-grid opacity-[0.06]"></div>

        <!-- Topo: logo -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/10 flex items-center justify-center font-semibold text-sm">
                AM
            </div>
            <span class="font-semibold tracking-tight">AnyDesk Manager</span>
        </div>

        <!-- Meio: ilustração + headline -->
        <div class="relative z-10 max-w-md">
            <svg viewBox="0 0 320 180" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto mb-10">
                <line x1="160" y1="90" x2="40" y2="30" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1" stroke-dasharray="4 4"/>
                <line x1="160" y1="90" x2="280" y2="30" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1" stroke-dasharray="4 4"/>
                <line x1="160" y1="90" x2="40" y2="150" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1" stroke-dasharray="4 4"/>
                <line x1="160" y1="90" x2="280" y2="150" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1" stroke-dasharray="4 4"/>
                <line x1="160" y1="90" x2="160" y2="18" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1" stroke-dasharray="4 4"/>
                <circle cx="160" cy="90" r="16" stroke="#ffffff" stroke-opacity="0.3" stroke-width="1"/>
                <circle cx="160" cy="90" r="7" fill="#ffffff"/>
                <circle cx="40" cy="30" r="4" fill="#ffffff" fill-opacity="0.7"/>
                <circle cx="280" cy="30" r="4" fill="#ffffff" fill-opacity="0.7"/>
                <circle cx="40" cy="150" r="4" fill="#ffffff" fill-opacity="0.7"/>
                <circle cx="280" cy="150" r="4" fill="#ffffff" fill-opacity="0.7"/>
                <circle cx="160" cy="18" r="4" fill="#ffffff" fill-opacity="0.7"/>
            </svg>
            <h2 class="text-2xl font-semibold tracking-tight leading-snug mb-3">Toda sessão de suporte remoto, sob controle.</h2>
            <p class="text-sm text-gray-400 leading-relaxed">Acompanhe conexões, equipe e histórico de atendimentos em um painel único, pensado para times de suporte técnico.</p>
        </div>

        <!-- Rodapé -->
        <p class="relative z-10 text-xs text-gray-500">&copy; {{ date('Y') }} Webline Sistemas — Todos os direitos reservados.</p>
    </div>

    <!-- Painel do formulário -->
    <div class="flex-1 flex items-center justify-center bg-white px-6 py-12 sm:px-10">
        <div class="w-full max-w-sm">

            <div class="mb-8">
                <div class="lg:hidden inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 text-white font-semibold text-lg shadow-sm mb-4">
                    AM
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Entrar</h1>
                <p class="text-sm text-gray-500 mt-1">Use suas credenciais de suporte para acessar o painel.</p>
            </div>

            <!-- Mensagem de status (ex: link de redefinição de senha enviado) -->
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-3.5 py-2.5 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- E-mail -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full bg-white border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition
                        @error('email') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="seu.email@empresa.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-medium" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Senha -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Senha</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900 transition">Esqueceu?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required
                        autocomplete="current-password"
                        class="w-full bg-white border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition
                        @error('password') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembrar-me -->
                <div class="flex items-center pt-1">
                    <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900 cursor-pointer">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Lembrar de mim</label>
                </div>

                <!-- Botão de acesso -->
                <button type="submit" class="w-full mt-2 bg-gray-900 hover:bg-gray-800 text-white font-medium text-sm py-2.5 px-4 rounded-lg transition shadow-sm">
                    Acessar painel
                </button>
            </form>

            <!-- Rodapé (visível apenas em telas pequenas, já que o painel de marca fica oculto) -->
            <p class="lg:hidden text-center text-xs text-gray-400 mt-10">
                &copy; {{ date('Y') }} Webline Sistemas — Todos os direitos reservados.
            </p>
        </div>
    </div>

</body>
</html>