<?php
if (!isset($pedido)) return;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação do Pedido</title>

    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 600;
        }

        .email-content {
            padding: 30px 20px;
        }

        .pedido-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 5px 5px 0;
        }

        .endereco-card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .item-pedido {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
        }

        .item-pedido:last-child {
            border-bottom: none;
        }

        .produto-nome {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .produto-detalhes {
            color: #6c757d;
            font-size: 0.9em;
        }

        .produto-preco {
            font-weight: 600;
            color: #28a745;
            font-size: 1.1em;
        }

        .total-section {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .total-final {
            border-top: 2px solid #007bff;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 1.2em;
            font-weight: 700;
            color: #007bff;
        }

        .desconto-text {
            color: #28a745 !important;
        }

        .info-importantes {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }

        .email-footer {
            background-color: #6c757d;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
        }

        /* Responsividade para dispositivos móveis */
        @media (max-width: 576px) {
            .email-container {
                margin: 0 10px;
                border-radius: 5px;
            }

            .email-header {
                padding: 20px 15px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .email-content {
                padding: 20px 15px;
            }

            .total-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .produto-info {
                margin-bottom: 10px;
            }
        }

        @media screen and (max-width: 480px) {
            .container-fluid {
                padding: 0 !important;
            }

            .row {
                margin: 0 !important;
            }

            .col-12 {
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="email-container">
                    <!-- Header -->
                    <div class="email-header">
                        <h1><i class="fas fa-check-circle me-2"></i>Pedido Confirmado!</h1>
                        <p class="mb-0 lead">Obrigado por sua compra, <?= htmlspecialchars($pedido->cliente_nome) ?>!</p>
                    </div>

                    <!-- Conteúdo Principal -->
                    <div class="email-content">
                        <!-- Informações do Pedido -->
                        <div class="pedido-info">
                            <h2 class="h4 mb-3 text-primary">
                                <i class="fas fa-file-invoice me-2"></i>
                                Detalhes do Pedido #<?= $pedido->numero_pedido ?>
                            </h2>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Data do Pedido:</strong><br>
                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($pedido->created_at)) ?></span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Status:</strong><br>
                                    <span class="badge bg-success"><?= ucfirst($pedido->status) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço de Entrega -->
                        <div class="endereco-card">
                            <h3 class="h5 mb-3 text-primary">
                                <i class="fas fa-shipping-fast me-2"></i>
                                Endereço de Entrega
                            </h3>
                            <address class="mb-0">
                                <strong><?= htmlspecialchars($pedido->cliente_nome) ?></strong><br>
                                <?= nl2br(htmlspecialchars($pedido->endereco)) ?><br>
                                <strong>CEP:</strong> <?= htmlspecialchars($pedido->cep) ?>
                            </address>
                        </div>

                        <!-- Itens do Pedido -->
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="h5 mb-0">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Itens do Pedido
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($pedido->itens)): ?>
                                    <?php foreach ($pedido->itens as $item): ?>
                                        <div class="item-pedido">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 produto-info">
                                                    <div class="produto-nome">
                                                        <?= htmlspecialchars($item->produto_nome ?? $item->nome_produto ?? 'Produto') ?>
                                                    </div>
                                                    <?php if (!empty($item->variacao) || !empty($item->nome_variacao)): ?>
                                                        <div class="produto-detalhes">
                                                            <small class="text-muted">
                                                                <i class="fas fa-tag me-1"></i>
                                                                Variação: <?= htmlspecialchars($item->variacao ?? $item->nome_variacao ?? '') ?>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="produto-detalhes">
                                                        <small>
                                                            <i class="fas fa-cubes me-1"></i>
                                                            Quantidade: <strong><?= $item->quantidade ?? 0 ?></strong> x
                                                            R$ <?= number_format($item->preco_unitario ?? 0, 2, ',', '.') ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="produto-preco">
                                                        R$ <?= number_format($item->subtotal ?? 0, 2, ',', '.') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-3 text-center text-muted">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        Nenhum item encontrado.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Resumo Financeiro -->
                        <div class="total-section">
                            <div class="total-row">
                                <span><i class="fas fa-calculator me-2"></i>Subtotal:</span>
                                <strong>R$ <?= number_format($pedido->subtotal, 2, ',', '.') ?></strong>
                            </div>

                            <?php if ($pedido->desconto > 0): ?>
                                <div class="total-row desconto-text">
                                    <span>
                                        <i class="fas fa-percentage me-2"></i>
                                        Desconto<?= $pedido->cupom_codigo ? ' (Cupom: ' . $pedido->cupom_codigo . ')' : '' ?>:
                                    </span>
                                    <strong>- R$ <?= number_format($pedido->desconto, 2, ',', '.') ?></strong>
                                </div>
                            <?php endif; ?>

                            <div class="total-row">
                                <span><i class="fas fa-truck me-2"></i>Frete:</span>
                                <strong>
                                    <?= $pedido->frete == 0 ? '<span class="badge bg-success">GRÁTIS</span>' : 'R$ ' . number_format($pedido->frete, 2, ',', '.') ?>
                                </strong>
                            </div>

                            <div class="total-row total-final">
                                <span><i class="fas fa-money-bill-wave me-2"></i>TOTAL:</span>
                                <strong>R$ <?= number_format($pedido->total, 2, ',', '.') ?></strong>
                            </div>
                        </div>

                        <!-- Informações Importantes -->
                        <div class="info-importantes">
                            <h4 class="h6 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Importantes
                            </h4>
                            <ul class="mb-0">
                                <li>Você receberá atualizações sobre o status do seu pedido por e-mail</li>
                                <li>O prazo de entrega será informado em breve</li>
                                <li>Em caso de dúvidas, entre em contato conosco</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="email-footer">
                        <p class="mb-2">Este é um e-mail automático, por favor não responda.</p>
                        <p class="mb-0">
                            <i class="fas fa-copyright me-1"></i>
                            <?= date('Y') ?> Reis ERP - Todos os direitos reservados
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome para ícones -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>