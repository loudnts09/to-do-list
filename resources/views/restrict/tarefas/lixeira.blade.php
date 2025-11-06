@extends('restrict.layouts.app')

@section('title', 'Lixeira de Tarefas')

@section('content')

    @include('restrict.partials._alerts')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lixeira de Tarefas</h2>
        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">
            &larr; Voltar para a Lista
        </a>
    </div>

    <div class="card">
        <div class="card-header">📋 Tarefas Excluídas</div>
        <div class="card-body">
            
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Excluída em</th>
                        <th width="150px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tarefas as $tarefa)
                        <tr>
                            <td>{{ $tarefa->title }}</td>
                            <td>{{ $tarefa->deleted_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ route('tarefas.restore', $tarefa->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Nenhuma tarefa na lixeira.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $tarefas->links() }}
            </div>

        </div>
    </div>
@endsection