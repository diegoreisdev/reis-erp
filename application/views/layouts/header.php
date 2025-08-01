<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reis ERP - <?= isset($page_title) ? $page_title : 'Produtos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <i class="fas fa-store"></i> Reis ERP
                </a>
                <div class="navbar-nav">
                    <a class="nav-link <?= $this->uri->segment(1) == 'produtos' || $this->uri->segment(1) == '' ? 'active' : '' ?>"
                        href="<?= base_url('produtos') ?>">
                        <i class="fas fa-box"></i> Produtos
                    </a>
                    <a class="nav-link <?= $this->uri->segment(1) == 'cupons' ? 'active' : '' ?>"
                        href="<?= base_url('cupons') ?>">
                        <i class="fas fa-ticket-alt"></i> Cupons
                    </a>
                </div>
            </div>
        </nav>