<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produto_model');
    }

    public function index()
    {
        $data['produtos'] = $this->Produto_model->get_all();
        $data['carrinho'] = $this->session->userdata('carrinho') ?: [];
        $data['page_title'] = 'Produtos';
        $data['scripts'] = ['assets/js/produto.js'];
        $this->load->view('produtos/index', $data);
    }

    public function get_produto($id)
    {
        $produto = $this->Produto_model->get_by_id($id);
        $estoque = $this->Produto_model->get_estoque($id);

        header('Content-Type: application/json');
        echo json_encode([
            'produto' => $produto,
            'estoque' => $estoque
        ]);
    }
}
