<?php $this->load->view('layouts/header'); ?>

<div class="container mt-4">
    <?php $this->load->view('layouts/messages'); ?>

    <div class="row">
        <!-- Lista de Produtos -->
        <div class="col-lg-8">
            <?php $this->load->view('produtos/lista_produtos', ['produtos' => $produtos]); ?>
        </div>

        <!-- Carrinho -->
        <div class="col-lg-4">
            <?php $this->load->view('carrinho/index', ['carrinho' => $carrinho]); ?>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>