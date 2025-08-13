<?php $this->load->view('layouts/header'); ?>

<div class="container mt-4">
    <?php $this->load->view('layouts/messages'); ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-ticket-alt"></i> Gerenciar Cupons</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cupomModal">
                <i class="fas fa-plus"></i> Novo Cupom
            </button>
        </div>
        <div class="card-body">
            <?php if (empty($cupons)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum cupom cadastrado.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cupomModal">
                        <i class="fas fa-plus"></i> Criar Primeiro Cupom
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-code"></i> Código</th>
                                <th><i class="fas fa-tag"></i> Tipo</th>
                                <th><i class="fas fa-percentage"></i> Valor</th>
                                <th><i class="fas fa-dollar-sign"></i> Valor Mínimo</th>
                                <th><i class="fas fa-calendar"></i> Período</th>
                                <th><i class="fas fa-toggle-on"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cupons as $cupom): ?>
                                <tr>
                                    <td>
                                        <code class="fs-6"><?= htmlspecialchars($cupom->codigo) ?></code>
                                    </td>
                                    <td>
                                        <?php if ($cupom->tipo === 'percentual'): ?>
                                            <span class="badge bg-info"><i class="fas fa-percentage"></i> Percentual</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning"><i class="fas fa-dollar-sign"></i> Fixo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <?php if ($cupom->tipo === 'percentual'): ?>
                                                <?= number_format($cupom->valor, 0) ?>%
                                            <?php else: ?>
                                                R$ <?= number_format($cupom->valor, 2, ',', '.') ?>
                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                    <td>R$ <?= number_format($cupom->valor_minimo, 2, ',', '.') ?></td>
                                    <td>
                                        <small>
                                            <i class="fas fa-calendar-plus text-success"></i>
                                            <?= date('d/m/Y', strtotime($cupom->data_inicio)) ?><br>
                                            <i class="fas fa-calendar-minus text-danger"></i>
                                            <?= date('d/m/Y', strtotime($cupom->data_fim)) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $hoje = date('Y-m-d');
                                        $ativo = $cupom->ativo && $cupom->data_inicio <= $hoje && $cupom->data_fim >= $hoje;
                                        ?>
                                        <?php if ($ativo): ?>
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Ativo</span>
                                        <?php elseif (!$cupom->ativo): ?>
                                            <span class="badge bg-secondary"><i class="fas fa-pause"></i> Inativo</span>
                                        <?php elseif ($cupom->data_inicio > $hoje): ?>
                                            <span class="badge bg-info"><i class="fas fa-clock"></i> Agendado</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> Expirado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary mx-1"
                                                data-editar-cupom="<?= $cupom->id ?>"
                                                title="Editar Cupom">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('cupons/deletar/' . $cupom->id) ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Tem certeza que deseja deletar este cupom?')"
                                                title="Deletar Cupom">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($cupons)): ?>
            <?php $message = count($cupons) > 1 ? 'cupons cadastrados' : 'cupom cadastrado' ?>
            <div class="card-footer text-muted">
                <small>
                    <i class="fas fa-info-circle"></i>
                    Total de <?= count($cupons) ?> <?= $message ?>
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('cupons/modal_cupom'); ?>
<?php $this->load->view('layouts/footer'); ?>