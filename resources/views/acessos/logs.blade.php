<!DOCTYPE html>
<html lang="pt-BR" :class="{ 'dark': darkMode }" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoria - AnyDesk Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', }</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen font-sans antialiased transition-colors duration-200">

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Cabeçalho com Menu -->
        <header class="mb-8 border-b border-slate-200 dark:border-slate-800 pb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">AnyDesk Manager</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Painel corporativo de controle e rastreio de acessos remotos (Suporte)</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition" title="Alternar Tema">
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <svg x-show="!darkMode" class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs bg-rose-500/10 text-rose-500 border border-rose-500/20 px-3 py-2 rounded-lg hover:bg-rose-500/20 transition font-medium">Sair</button>
                    </form>
                </div>
            </div>

            <!-- Menu de Navegação -->
            <nav class="flex gap-6 mt-2">
                <a href="{{ route('acessos.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 pb-2 transition border-b-2 border-transparent">
                    Acessos Remotos
                </a>
                <a href="{{ route('acessos.logs') }}" class="text-sm font-medium text-indigo-500 border-b-2 border-indigo-500 pb-2">
                    Auditoria (Logs)
                </a>
            </nav>
        </header>

        <!-- Tabela Exclusiva de Logs -->
        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-xl">
            <h2 class="text-lg font-semibold mb-6">Registro Geral de Atividades da Equipe</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700/50 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3 px-3">Data / Hora</th>
                            <th class="pb-3 px-3">Usuário</th>
                            <th class="pb-3 px-3">Ação Realizada</th>
                            <th class="pb-3 px-3">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td class="py-3 px-3 font-medium text-indigo-500">{{ $log->usuario }}</td>
                                <td class="py-3 px-3">
                                    @php
                                        $corAcao = match($log->acao) {
                                            'Criação' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                            'Edição' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                            'Exclusão' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                        };
                                    @endphp
                                    <span class="{{ $corAcao }} border px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide">
                                        {{ $log->acao }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-600 dark:text-slate-300">{{ $log->detalhes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 dark:text-slate-500 text-sm">
                                    Nenhuma atividade registrada no sistema ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação dos Logs -->
            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

</body>
</html>