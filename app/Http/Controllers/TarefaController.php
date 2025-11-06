<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = Tarefa::where('user_id', $userId);

        if ($request->has('status') && $request->input('status') != null) { 
            $query->where('status', $request->input('status'));
        }
        $tarefas = $query->paginate(10); 

        return view('restrict.tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        return view('restrict.tarefas.create');
    }

    /**
     * salvar tarefa usando o FormRequest para validar. [4, 17]
     */
    public function store(StoreTarefaRequest $request)
    {
        $user = $request->user();
        $user->tarefas()->create($request->validated());

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * mostrar o form de edição. [10, 25]
     */
    public function edit(Tarefa $tarefa)
    {
        if (Auth::id()!== $tarefa->user_id) {
           abort(404); // Falha de segurança
        }
        return view('restrict.tarefas.edit', compact('tarefa'));
    }

    /**
     * atualizar a tarefa usando o FormRequest para validar.[11, 17]
     */
    public function update(UpdateTarefaRequest $request, Tarefa $tarefa){

        $tarefa->update($request->validated());

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * excluir tarefa com soft delete. [13, 15]
     */
    public function destroy(Tarefa $tarefa)
    {
        if (Auth::id() !== $tarefa->user_id) {
            abort(404);
        }
        $tarefa->delete();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa movida para a lixeira!');
    }

    public function lixeira()
    {
        $tarefas = Tarefa::where('user_id', Auth::id())
                        ->onlyTrashed()
                        ->paginate(10);

        return view('restrict.tarefas.lixeira', compact('tarefas'));
    }

    public function restore($id)
    {
        $tarefa = Tarefa::withTrashed()->findOrFail($id);

        if (Auth::id() !== $tarefa->user_id) {
            abort(404);
        }
        $tarefa->restore();

        return redirect()->route('tarefas.lixeira')->with('success', 'Tarefa restaurada com sucesso!');
    }
}
