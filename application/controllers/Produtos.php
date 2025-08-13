<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Produto_model $Produto_model
 * @property Estoque_model $Estoque_model
 * @property CI_Session $session
 * @property CI_Input $input
 */
class Produtos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produto_model', 'Estoque_model']);
    }

    /**
     * Lista todos os produtos e renderiza a view
     *
     * @return void
     */
    public function index(): void
    {
        $data = [
            'produtos'   => $this->Produto_model->get_all(),
            'carrinho'   => $this->session->userdata('carrinho') ?: [],
            'page_title' => 'Produtos',
            'scripts'    => ['assets/js/produto.js', 'assets/js/carrinho.js', 'assets/js/checkout.js']
        ];

        $this->load->view('produtos/index', $data);
    }

    /**
     * Retorna um produto e seu estoque em JSON
     *
     * @param int $id
     * @return void
     */
    public function get_produto(int $id): void
    {
        $produto = $this->Produto_model->get_by_id($id);
        $estoque = $this->Estoque_model->get_estoque($id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'produto' => $produto,
                'estoque' => $estoque
            ]));
    }

    /**
     * Salva ou atualiza um produto e suas variações de estoque
     *
     * @return void
     */
    public function salvar(): void
    {
        $mensagem = '';

        $produto_data = [
            'nome'      => $this->input->post('nome'),
            'preco'     => $this->input->post('preco'),
            'descricao' => $this->input->post('descricao')
        ];

        $produto_id = (int) $this->input->post('produto_id');

        if ($produto_id) {
            $this->Produto_model->update($produto_id, $produto_data);
            $mensagem = 'Produto atualizado com sucesso';
        } else {
            $this->Produto_model->insert($produto_data);
            $produto_id = $this->db->insert_id();
            $mensagem   = 'Produto adicionado com sucesso';
        }

        $variacoes   = $this->input->post('variacoes');
        $quantidades = $this->input->post('quantidades');
        $estoque_ids = $this->input->post('estoque_ids');

        if (is_array($variacoes) && is_array($quantidades)) {
            foreach ($variacoes as $key => $variacao) {
                if (!empty($variacao) && isset($quantidades[$key])) {
                    $estoque_data = [
                        'produto_id' => $produto_id,
                        'variacao'   => $variacao,
                        'quantidade' => (int) $quantidades[$key]
                    ];

                    if (!empty($estoque_ids[$key])) {
                        $this->Estoque_model->update_estoque((int) $estoque_ids[$key], $estoque_data);
                    } else {
                        $this->Estoque_model->insert_estoque($estoque_data);
                    }
                }
            }
        }

        $this->session->set_flashdata('sucesso', $mensagem);
        redirect('produtos');
    }
}
