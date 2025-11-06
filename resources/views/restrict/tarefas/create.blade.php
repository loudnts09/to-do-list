@extends('restrict.layouts.app')

@section('title', 'Nova Tarefa')

@section('content')
    <div class="card">
        <div class="card-header">Criar Nova Tarefa</div>
        <div class="card-body">

            @include('restrict.partials._alerts')

            <form action="{{ route('tarefas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Título *</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status') ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">
                        Marcar como Concluída
                    </label>
                </div>

                <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
@endsection