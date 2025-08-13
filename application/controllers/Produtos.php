<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produto_model', 'Estoque_model']);
    }

    public function index()
    {
        $data['produtos']   = $this->Produto_model->get_all();
        $data['carrinho']   = $this->session->userdata('carrinho') ?: [];
        $data['page_title'] = 'Produtos';
        $data['scripts']    = ['assets/js/produto.js', 'assets/js/carrinho.js', 'assets/js/checkout.js'];
        $this->load->view('produtos/index', $data);
    }

    public function get_produto($id)
    {
        $produto = $this->Produto_model->get_by_id($id);
        $estoque = $this->Estoque_model->get_estoque($id);

        header('Content-Type: application/json');
        echo json_encode([
            'produto' => $produto,
            'estoque' => $estoque
        ]);
    }

    public function salvar()
    {
        $mensagem = '';

        $produto_data = array(
            'nome'      => $this->input->post('nome'),
            'preco'     => $this->input->post('preco'),
            'descricao' => $this->input->post('descricao')
        );

        $produto_id = $this->input->post('produto_id');

        if ($produto_id) {
            // Update
            $this->Produto_model->update($produto_id, $produto_data);
            $mensagem = 'Produto atualizado com sucesso';
        } else {
            // Insert
            $this->Produto_model->insert($produto_data);
            $produto_id = $this->db->insert_id();
            $mensagem   = 'Produto adicionado com sucesso';
        }

        // Processar variações de estoque
        $variacoes   = $this->input->post('variacoes');
        $quantidades = $this->input->post('quantidades');
        $estoque_ids = $this->input->post('estoque_ids');

        if ($variacoes && $quantidades) {
            foreach ($variacoes as $key => $variacao) {
                if (!empty($variacao) && isset($quantidades[$key])) {
                    $estoque_data = array(
                        'produto_id' => $produto_id,
                        'variacao'   => $variacao,
                        'quantidade' => $quantidades[$key]
                    );

                    if (isset($estoque_ids[$key]) && !empty($estoque_ids[$key])) {
                        // Update estoque existente
                        $this->Estoque_model->update_estoque($estoque_ids[$key], $estoque_data);
                    } else {
                        // Novo estoque
                        $this->Estoque_model->insert_estoque($estoque_data);
                    }
                }
            }
        }
        $this->session->set_flashdata('sucesso', $mensagem);
        redirect('produtos');
    }
}
