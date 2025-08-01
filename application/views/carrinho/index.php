<div class="card sticky-top" style="top: 20px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-shopping-cart"></i> Carrinho</h5>
        <span class="badge bg-primary fs-6" id="carrinho-count"><?= count($carrinho) ?></span>
    </div>
    <div class="card-body" id="carrinho-content">
        <!-- Carrinho vazio -->
        <?php if (empty($carrinho)): ?>
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                <p class="text-muted mb-0">Seu carrinho está vazio</p>
                <small class="text-muted">Adicione produtos para continuar</small>
            </div>

            <!-- Carrinho com produtos -->
        <?php else: ?>
            <div class="carrinho-items" style="max-height: 300px; overflow-y: auto;">
                <?php
                $subtotal = 0;
                foreach ($carrinho as $key => $item):
                    $item_total = $item['preco'] * $item['quantidade'];
                    $subtotal += $item_total;
                ?>
                    <div class="carrinho-item mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= htmlspecialchars($item['nome']) ?></h6>
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge bg-secondary me-2"><?= htmlspecialchars($item['variacao']) ?></span>
                                    <small class="text-muted">Qtd: <?= $item['quantidade'] ?></small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        R$ <?= number_format($item['preco'], 2, ',', '.') ?> cada
                                    </small>
                                    <strong class="text-success">
                                        R$ <?= number_format($item_total, 2, ',', '.') ?>
                                    </strong>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger ms-2"
                                onclick="removerItem('<?= $key ?>')"
                                title="Remover item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr>

            <!-- Subtotal -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Subtotal:</h6>
                <h5 class="mb-0 text-primary">
                    R$ <span id="subtotal"><?= number_format($subtotal, 2, ',', '.') ?></span>
                </h5>
            </div>

            <!-- Ações -->
            <div class="d-grid gap-2">
                <button class="btn btn-success btn-lg"
                    data-bs-toggle="modal"
                    data-bs-target="#checkoutModal">
                    <i class="fas fa-credit-card"></i> Finalizar Pedido
                </button>
                <button class="btn btn-outline-secondary btn-sm"
                    onclick="limparCarrinho()"
                    title="Limpar carrinho">
                    <i class="fas fa-trash"></i> Limpar Carrinho
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quantidade de itens -->
    <?php if (!empty($carrinho)): ?>
        <?php $item = count($carrinho) === 1 ? 'item' : 'itens'  ?>

        <div class="card-footer">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                <?= count($carrinho) ?> <?= $item ?> no carrinho
            </small>
        </div>
    <?php endif; ?>
</div>