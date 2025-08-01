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
        $data['carrinho'] = $this->session->userdata('carrinho') ?: array();
        $this->load->view('produtos/index', $data);
    }
}
