<?php $this->load->view('layouts/header'); ?>

<div class="container mt-4">
    <div class="row">
        <!-- Lista de Produtos -->
        <div class="col-lg-8">
            <?php $this->load->view('produtos/lista_produtos', ['produtos' => $produtos]); ?>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>