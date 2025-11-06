<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Tem certeza que deseja excluir esta tarefa? Esta ação não pode ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a
>
        <form id="deleteForm" method="POST" action=""> 
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Sim, Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>