<div class="modal fade" id="produtoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="produtoForm" method="post" action="<?= base_url('produtos/salvar') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-box"></i> <span id="modal-title-text">Novo Produto</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produto_id" id="produto_id">

                    <!-- Dados Básicos -->
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Nome do Produto *
                            </label>
                            <input type="text" class="form-control" name="nome" id="nome"
                                placeholder="Ex: Camiseta Básica" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i> Preço *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" name="preco" id="preco"
                                    step="0.01" min="0" placeholder="0,00" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">
                            <i class="fas fa-align-left"></i> Descrição
                        </label>
                        <textarea class="form-control" name="descricao" id="descricao" rows="3"
                            placeholder="Descrição detalhada do produto (opcional)"></textarea>
                    </div>

                    <hr>

                    <!-- Variações e Estoque -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">
                                <i class="fas fa-warehouse"></i> Variações e Estoque
                            </label>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                Adicione as variações do produto (tamanhos, cores, etc.) e suas respectivas quantidades em estoque.
                            </small>
                        </div>

                        <div id="variacoes-container">
                            <!-- Variações serão adicionadas aqui via JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-title-text">
                        <i class="fas fa-save"></i> Salvar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>