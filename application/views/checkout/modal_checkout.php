<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?= base_url('produtos/finalizar_pedido') ?>" id="checkoutForm">
                <?php $subtotal = 0; ?>
                <?php foreach ($carrinho as $item): ?>
                    <?php $subtotal += $item['preco'] * $item['quantidade']; ?>
                <?php endforeach; ?>

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-credit-card"></i> Finalizar Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Dados do Cliente -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-user"></i> Dados do Cliente</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nome"
                                        placeholder="Seu nome completo" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="seu@email.com" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-control" name="telefone"
                                        placeholder="(11) 99999-9999">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço de Entrega -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Endereço de Entrega</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">CEP <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cep" id="checkout_cep"
                                            placeholder="00000-000" maxlength="9" required>
                                        <button type="button" class="btn btn-outline-primary" onclick="buscarCep()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Endereço Completo <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="endereco_completo" id="endereco_completo"
                                    rows="3" placeholder="Rua, número, complemento, bairro, cidade - UF" required></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Use o botão de busca no CEP para preenchimento automático
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cupom de Desconto -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-ticket-alt"></i> Cupom de Desconto</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="cupom_codigo"
                                        placeholder="Digite o código do cupom">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100" onclick="aplicarCupom()">
                                        <i class="fas fa-check"></i> Aplicar
                                    </button>
                                </div>
                            </div>
                            <div id="cupom_feedback" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Resumo do Pedido -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-receipt"></i> Resumo do Pedido</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><i class="fas fa-shopping-bag"></i> Subtotal:</td>
                                    <td class="text-end">R$ <span id="checkout_subtotal"><?= number_format($subtotal, 2, ',', '.') ?></span>
                                    </td>
                                </tr>
                                <tr id="desconto_row" style="display: none;">
                                    <td><i class="fas fa-tag text-success"></i> Desconto:</td>
                                    <td class="text-end text-success">- R$ <span id="checkout_desconto">0,00</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-truck"></i> Frete:</td>
                                    <td class="text-end">R$ <span id="checkout_frete">0,00</span></td>
                                </tr>
                                <tr class="table-dark">
                                    <td><strong><i class="fas fa-calculator"></i> Total:</strong></td>
                                    <td class="text-end"><strong>R$ <span id="checkout_total">0,00</span></strong></td>
                                </tr>
                            </table>

                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Regras de Frete:</strong><br>
                                    • Pedidos acima de R$ 200,00: <strong>Frete Grátis</strong><br>
                                    • Pedidos entre R$ 52,00 e R$ 166,59: <strong>R$ 15,00</strong><br>
                                    • Demais valores: <strong>R$ 20,00</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-credit-card"></i> Finalizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>