<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-box"></i> Produtos</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#produtoModal">
            <i class="fas fa-plus"></i> Novo Produto
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($produtos)): ?>
            <div class="text-center py-4">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#produtoModal">
                    <i class="fas fa-plus"></i> Cadastrar o Primeiro Produto
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-tag"></i> Nome</th>
                            <th><i class="fas fa-dollar-sign"></i> Preço</th>
                            <th><i class="fas fa-warehouse"></i> Estoque</th>
                            <th><i class="fas fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($produto->nome) ?></strong>
                                    <?php if (!empty($produto->descricao)): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($produto->descricao) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>
                                        R$ <?= number_format($produto->preco, 2, ',', '.') ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if ($produto->estoque_info): ?>
                                        <?php
                                        $estoques = explode('|', $produto->estoque_info);
                                        $total_estoque = 0;
                                        foreach ($estoques as $estoque):
                                            $parts = explode(':', $estoque);
                                            $total_estoque += (int)$parts[1];
                                            $cor_badge = (int)$parts[1] > 15 ? 'bg-success' : ((int)$parts[1] > 10 ? 'bg-warning' : 'bg-danger');
                                            echo '<span class="badge ' . $cor_badge . ' me-1">' . $parts[0] . ': ' . $parts[1] . '</span>';
                                        endforeach;
                                        ?>
                                        <br><small class="text-muted">Total: <?= $total_estoque ?> unidades</small>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Sem estoque</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary mx-1"
                                            onclick="editarProduto(<?= $produto->id ?>)"
                                            title="Editar Produto">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="mostrarCompra(<?= $produto->id ?>)"
                                            title="Comprar Produto"
                                            <?= empty($total_estoque) ? 'disabled' : '' ?>>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($produtos)): ?>
        <div class="card-footer text-muted">
            <small>
                <i class="fas fa-info-circle"></i>
                Total de <?= count($produtos) ?> produto(s) cadastrado(s)
            </small>
        </div>
    <?php endif; ?>
</div>