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
        $data['produtos']   = $this->Produto_model->get_all();
        $data['carrinho']   = $this->session->userdata('carrinho') ?: [];
        $data['page_title'] = 'Produtos';
        $data['scripts']    = ['assets/js/produto.js', 'assets/js/carrinho.js', 'assets/js/checkout.js'];
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

    public function salvar()
    {
        $mensagem = '';

        $produto_data = array(
            'nome' => $this->input->post('nome'),
            'preco' => $this->input->post('preco'),
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
            $mensagem = 'Produto adicionado com sucesso';
        }

        // Processar variações de estoque
        $variacoes = $this->input->post('variacoes');
        $quantidades = $this->input->post('quantidades');
        $estoque_ids = $this->input->post('estoque_ids');

        if ($variacoes && $quantidades) {
            foreach ($variacoes as $key => $variacao) {
                if (!empty($variacao) && isset($quantidades[$key])) {
                    $estoque_data = array(
                        'produto_id' => $produto_id,
                        'variacao' => $variacao,
                        'quantidade' => $quantidades[$key]
                    );

                    if (isset($estoque_ids[$key]) && !empty($estoque_ids[$key])) {
                        // Update estoque existente
                        $this->Produto_model->update_estoque($estoque_ids[$key], $estoque_data);
                    } else {
                        // Novo estoque
                        $this->Produto_model->insert_estoque($estoque_data);
                    }
                }
            }
        }
        $this->session->set_flashdata('sucesso', $mensagem);
        redirect('produtos');
    }

    public function adicionar_carrinho()
    {
        $produto_id = $this->input->post('produto_id');
        $variacao   = $this->input->post('variacao');
        $quantidade = $this->input->post('quantidade');

        // Verificar estoque
        if (!$this->Produto_model->verificar_estoque($produto_id, $variacao, $quantidade)) {
            echo json_encode(array('success' => false, 'message' => 'Estoque insuficiente'));
            return;
        }

        $produto  = $this->Produto_model->get_by_id($produto_id);
        $carrinho = $this->session->userdata('carrinho') ?: array();

        $item_key = $produto_id . '_' . $variacao;

        if (isset($carrinho[$item_key])) {
            $carrinho[$item_key]['quantidade'] += $quantidade;
        } else {
            $carrinho[$item_key] = array(
                'produto_id' => $produto_id,
                'nome'       => $produto->nome,
                'variacao'   => $variacao,
                'preco'      => $produto->preco,
                'quantidade' => $quantidade
            );
        }

        $this->session->set_userdata('carrinho', $carrinho);
        echo json_encode(array('success' => true, 'message' => 'Produto adicionado ao carrinho'));
    }

    public function remover_item_carrinho()
    {
        $item_key = $this->input->post('item_key');
        $carrinho = $this->session->userdata('carrinho') ?: array();

        if (isset($carrinho[$item_key])) {
            unset($carrinho[$item_key]);
            $this->session->set_userdata('carrinho', $carrinho);
        }

        echo json_encode(array('success' => true));
    }

    public function remover_carrinho()
    {
        $carrinho = $this->session->userdata('carrinho');

        if (isset($carrinho)) $this->session->unset_userdata('carrinho');

        echo json_encode(array('success' => true));
    }

    public function verificar_cep()
    {
        $cep = $this->input->post('cep');
        $cep = preg_replace('/[^0-9]/', '', $cep);

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = file_get_contents($url);

        header('Content-Type: application/json');
        echo $response;
    }
}
