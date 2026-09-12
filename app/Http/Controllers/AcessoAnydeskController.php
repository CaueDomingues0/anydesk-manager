<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcessoAnydesk;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AcessoAnydeskController extends Controller
{
    /**
     * Exibe a listagem de acessos com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $acessos = AcessoAnydesk::when($search, function ($query, $search) {
            return $query->where('nome_cliente', 'like', "%{$search}%")
                         ->orWhere('codigo_anydesk', 'like', "%{$search}%")
                         ->orWhere('cidade_orgao', 'like', "%{$search}%");
        })
        ->orderBy('favorito', 'desc')
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('acessos.index', compact('acessos', 'search'));
    }

    /**
     * Cadastra um novo acesso e registra a ação no log de auditoria.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'codigo_anydesk' => 'required|string|max:255',
        ]);

        AcessoAnydesk::create($request->all());

        ActivityLog::create([
            'usuario' => Auth::user()->name ?? 'Sistema',
            'acao' => 'Criação',
            'detalhes' => 'Cadastrou o cliente: ' . $request->nome_cliente,
        ]);

        return redirect()->route('acessos.index')->with('sucesso', 'Acesso cadastrado com sucesso!');
    }

    /**
     * Carrega o registro selecionado para edição na interface.
     */
    public function edit($id)
    {
        $acessoEditando = AcessoAnydesk::findOrFail($id);
        $search = request('search');
        
        $acessos = AcessoAnydesk::when($search, function ($query, $search) {
            return $query->where('nome_cliente', 'like', "%{$search}%")
                         ->orWhere('codigo_anydesk', 'like', "%{$search}%")
                         ->orWhere('cidade_orgao', 'like', "%{$search}%");
        })
        ->orderBy('favorito', 'desc')
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('acessos.index', compact('acessos', 'acessoEditando', 'search'));
    }

    /**
     * Atualiza o registro e gera log detalhado com base nas alterações (antes e depois).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'codigo_anydesk' => 'required|string|max:255',
        ]);

        $acesso = AcessoAnydesk::findOrFail($id);
        $acesso->fill($request->all());

        $mudancas = [];
        
        if ($acesso->isDirty()) {
            foreach ($acesso->getDirty() as $campo => $novoValor) {
                $valorAntigo = $acesso->getOriginal($campo) ?? 'Vazio';
                $novoValor = $novoValor ?? 'Vazio';
                $campoNome = ucfirst(str_replace('_', ' ', $campo));

                $mudancas[] = "{$campoNome} de '{$valorAntigo}' para '{$novoValor}'";
            }
        }

        $acesso->save();

        $detalhesLog = 'Editou: ' . $acesso->nome_cliente;
        if (!empty($mudancas)) {
            $detalhesLog .= ' -> ' . implode(' | ', $mudancas);
        } else {
            $detalhesLog .= ' (Salvo sem alterações)';
        }

        $detalhesLog = \Illuminate\Support\Str::limit($detalhesLog, 250);

        ActivityLog::create([
            'usuario' => Auth::user()->name ?? 'Sistema',
            'acao' => 'Edição',
            'detalhes' => $detalhesLog,
        ]);

        return redirect()->route('acessos.index')->with('sucesso', 'Acesso atualizado com sucesso!');
    }

    /**
     * Remove o registro do sistema e registra a exclusão na auditoria.
     */
    public function destroy($id)
    {
        $acesso = AcessoAnydesk::findOrFail($id);
        $nomeCliente = $acesso->nome_cliente;
        $acesso->delete();

        ActivityLog::create([
            'usuario' => Auth::user()->name ?? 'Sistema',
            'acao' => 'Exclusão',
            'detalhes' => 'Removeu o cliente: ' . $nomeCliente,
        ]);

        return redirect()->route('acessos.index')->with('sucesso', 'Acesso removido com sucesso!');
    }

    /**
     * Alterna o status de fixado/favorito do acesso.
     */
    public function toggleFavorito($id)
    {
        $acesso = AcessoAnydesk::findOrFail($id);
        $acesso->favorito = !$acesso->favorito;
        $acesso->save();

        return redirect()->back();
    }

    /**
     * Exibe o histórico de logs de auditoria do sistema.
     */
    public function logs()
    {
        $logs = ActivityLog::latest()->paginate(30);
        
        return view('acessos.logs', compact('logs'));
    }
}