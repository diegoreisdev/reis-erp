<!-- Modal Cupom -->
<div class="modal fade" id="cupomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cupomForm" method="post" action="<?= base_url('cupons/salvar') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-ticket-alt"></i> <span id="modal-cupom-title">Novo Cupom de Desconto</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="cupom_id" id="cupom_id">

                    <!-- Código do Cupom -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-code"></i> Código do Cupom *
                        </label>
                        <input type="text" class="form-control text-uppercase" name="codigo" id="codigo"
                            placeholder="Ex: DESC10, FRETE15, PROMO20" maxlength="50" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Use apenas letras, números e símbolos. Será convertido para maiúsculo.
                        </div>
                    </div>

                    <!-- Tipo e Valor -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-tag"></i> Tipo de Desconto *
                            </label>
                            <select class="form-select" name="tipo" id="tipo" required onchange="alterarTipo()">
                                <option value="">Selecione o tipo</option>
                                <option value="percentual">
                                    <i class="fas fa-percentage"></i> Percentual (%)
                                </option>
                                <option value="fixo">
                                    <i class="fas fa-dollar-sign"></i> Valor Fixo (R$)
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calculator"></i> Valor do Desconto *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" id="valor-prefix">R$</span>
                                <input type="number" class="form-control" name="valor" id="valor"
                                    step="0.01" min="0" placeholder="0,00" required>
                            </div>
                            <div class="form-text">
                                <small id="valor-help">Digite o valor do desconto</small>
                            </div>
                        </div>
                    </div>

                    <!-- Valor Mínimo -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-shopping-bag"></i> Valor Mínimo do Pedido
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" name="valor_minimo" id="valor_minimo"
                                step="0.01" min="0" value="0" placeholder="0,00">
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Valor mínimo do carrinho para aplicar o cupom. Use 0 para sem restrição.
                        </div>
                    </div>

                    <!-- Período de Validade -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-plus"></i> Data de Início *
                            </label>
                            <input type="date" class="form-control" name="data_inicio" id="data_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-minus"></i> Data de Fim *
                            </label>
                            <input type="date" class="form-control" name="data_fim" id="data_fim" required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="ativo" id="ativo" checked>
                            <label class="form-check-label" for="ativo">Cupom ativo</label>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Cupons inativos não poderão ser utilizados pelos clientes.
                        </div>
                    </div>

                    <!-- Preview do Cupom -->
                    <div class="mt-4">
                        <div class="alert alert-light border" id="cupom-preview" style="display: none;">
                            <h6><i class="fas fa-eye"></i> Preview do Cupom:</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code id="preview-codigo">CODIGO</code>
                                    <span class="badge" id="preview-tipo">Tipo</span>
                                </div>
                                <div class="text-end">
                                    <strong id="preview-desconto">Desconto</strong><br>
                                    <small class="text-muted" id="preview-minimo">Mínimo: R$ 0,00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Cupom
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>