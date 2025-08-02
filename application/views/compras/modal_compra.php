<!-- Modal Compra -->
<div class="modal fade" id="compraModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="compraForm">
                    <input type="hidden" id="compra_produto_id">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-box"></i> Produto
                        </label>
                        <input type="text" class="form-control" id="compra_produto_nome" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tags"></i> Variação *
                        </label>
                        <select class="form-select" id="compra_variacao" required>
                            <option value="">Selecione uma variação</option>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Apenas variações com estoque disponível são exibidas
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-sort-numeric-up"></i> Quantidade *
                            </label>
                            <input type="number" class="form-control" id="compra_quantidade"
                                min="1" max="999" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i> Preço Unitário
                            </label>
                            <input type="text" class="form-control bg-light" id="compra_preco" readonly>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="alert alert-light border">
                            <div class="d-flex justify-content-between">
                                <span><strong>Total do Item:</strong></span>
                                <span class="text-success fw-bold" id="total_item">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" data-add-carrinho>
                    <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                </button>
            </div>
        </div>
    </div>
</div>