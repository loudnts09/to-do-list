@extends('restrict.layouts.app')

@section('title', 'Minhas Tarefas')

@section('content')

    @include('restrict.partials._alerts')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Minhas Tarefas</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('tarefas.lixeira') }}" class="btn btn-outline-danger">
                🗑️ Lixeira
            </a>
            <a href="{{ route('tarefas.create') }}" class="btn btn-primary">
                + Nova Tarefa
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">📋 Lista de Tarefas</div>
        <div class="card-body">
            
            <form method="GET" action="{{ route('tarefas.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Filtrar por status --</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pendente</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Concluída</option>
                        </select>
                    </div>
                </div>
            </form>
            
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Criação</th>
                        <th width="150px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tarefas as $tarefa)
                        <tr>
                            <td>{{ $tarefa->title }}</td>
                            <td>
                                @if($tarefa->status)
                                    <span class="badge bg-success">Concluída</span>
                                @else
                                    <span class="badge bg-warning">Pendente</span>
                                @endif
                            </td>
                            <td>{{ $tarefa->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-sm btn-warning">Editar</a>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-action="{{ route('tarefas.destroy', $tarefa) }}">Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Nenhuma tarefa encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $tarefas->appends(request()->query())->links() }}
            </div>

        </div>
    </div>

    @include('restrict.partials._delete_modal')

@endsection