<!DOCTYPE html>
<html lang="pt-BR" :class="{ 'dark': darkMode }" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - AnyDesk Manager</title>
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
                    <div class="text-right hidden sm:block">
                        <span class="block text-xs text-slate-400">Logado como:</span>
                        <span class="text-sm font-semibold text-indigo-500">{{ Auth::user()->name ?? 'Administrador' }}</span>
                    </div>

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
                <a href="{{ route('acessos.index') }}" class="text-sm font-medium text-indigo-500 border-b-2 border-indigo-500 pb-2">
                    Acessos Remotos
                </a>
                <a href="{{ route('acessos.logs') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 pb-2 transition border-b-2 border-transparent">
                    Auditoria (Logs)
                </a>
            </nav>
        </header>

        @if(session('sucesso'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">
                {{ session('sucesso') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Formulário de Cadastro / Edição -->
            <div class="lg:col-span-1 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl p-6 h-fit shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">{{ isset($acessoEditando) ? 'Editar Acesso' : 'Novo Acesso' }}</h2>
                    @isset($acessoEditando)
                        <a href="{{ route('acessos.index') }}" class="text-xs text-slate-400 hover:underline">Cancelar</a>
                    @endisset
                </div>
                
                <form action="{{ isset($acessoEditando) ? route('acessos.update', $acessoEditando->id) : route('acessos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @isset($acessoEditando) @method('PUT') @endisset

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Cliente / Órgão</label>
                        <input type="text" name="nome_cliente" required value="{{ $acessoEditando->nome_cliente ?? old('nome_cliente') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Código AnyDesk</label>
                        <input type="text" name="codigo_anydesk" required value="{{ $acessoEditando->codigo_anydesk ?? old('codigo_anydesk') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Cidade / Setor (Opc)</label>
                        <input type="text" name="cidade_orgao" value="{{ $acessoEditando->cidade_orgao ?? old('cidade_orgao') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Obs / Senha (Opc)</label>
                        <textarea name="observacoes" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 transition">{{ $acessoEditando->observacoes ?? old('observacoes') }}</textarea>
                    </div>

                    <button type="submit" class="w-full {{ isset($acessoEditando) ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/20' : 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-600/20' }} text-white font-medium py-2 rounded-lg text-sm transition shadow-lg">
                        {{ isset($acessoEditando) ? 'Salvar Alterações' : 'Cadastrar Acesso' }}
                    </button>
                </form>
            </div>

            <!-- Tabela de Listagem -->
            <div class="lg:col-span-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-xl flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-lg font-semibold">Acessos Cadastrados</h2>

                    <form action="{{ route('acessos.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar cliente, código ou cidade..." class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-indigo-500 transition w-full sm:w-64">
                        <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-1.5 rounded-lg text-sm transition">Buscar</button>
                        @if(isset($search) && $search != '')
                            <a href="{{ route('acessos.index') }}" class="bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-white px-3 py-1.5 rounded-lg text-sm transition flex items-center">Limpar</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700/50 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 px-2 text-center w-10">Fav</th>
                                <th class="pb-3 px-3">Cliente</th>
                                <th class="pb-3 px-3">AnyDesk</th>
                                <th class="pb-3 px-3">Cidade/Setor</th>
                                <th class="pb-3 px-3">Senha / Obs</th>
                                <th class="pb-3 px-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                            @forelse($acessos as $acesso)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                    <td class="py-3 px-2 text-center">
                                        <form action="{{ route('acessos.favorito', $acesso->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-lg transition hover:scale-110" title="Fixar no topo">{!! $acesso->favorito ? '⭐' : '☆' !!}</button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-3 font-medium">{{ $acesso->nome_cliente }}</td>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 border border-indigo-500/20 px-2 py-1 rounded font-mono text-xs">{{ $acesso->codigo_anydesk }}</span>
                                            <button onclick="copiarCodigo('{{ $acesso->codigo_anydesk }}', this)" class="text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 px-2 py-1 rounded transition">Copiar</button>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $acesso->cidade_orgao ?? '-' }}</td>
                                    <td class="py-3 px-3 text-slate-600 dark:text-slate-300">
                                        @if($acesso->observacoes)
                                            <span class="bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-2.5 py-1 rounded text-xs text-amber-500 dark:text-amber-300 font-mono inline-block">{{ $acesso->observacoes }}</span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('acessos.edit', $acesso->id) }}" class="bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 dark:text-amber-400 border border-amber-500/30 px-2.5 py-1 rounded text-xs transition">Editar</a>
                                            <form action="{{ route('acessos.destroy', $acesso->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar este acesso?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 dark:text-rose-400 border border-rose-500/30 px-2.5 py-1 rounded text-xs transition">Apagar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500 text-sm">Nenhum acesso encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">{{ $acessos->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        function copiarCodigo(codigo, botao) {
            navigator.clipboard.writeText(codigo).then(() => {
                const textoOriginal = botao.innerText;
                botao.innerText = 'Copiado!';
                botao.classList.add('bg-emerald-500', 'text-white');
                setTimeout(() => {
                    botao.innerText = textoOriginal;
                    botao.classList.remove('bg-emerald-500', 'text-white');
                }, 1500);
            });
        }
    </script>
</body>
</html>