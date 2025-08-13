<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Estoque_model $Estoque_model
 * @property Produto_model $Produto_model
 * @property CI_Session    $session
 * @property CI_Input      $input
 */
class Carrinho extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Estoque_model', 'Produto_model']);
    }

    /**
     * Adiciona um produto ao carrinho
     *
     * @return void
     */
    public function adicionar_carrinho(): void
    {
        $produto_id = (int) $this->input->post('produto_id');
        $variacao   = trim((string) $this->input->post('variacao'));
        $quantidade = (int) $this->input->post('quantidade');

        // Verificar estoque
        if (!$this->Estoque_model->verificar_estoque($produto_id, $variacao, $quantidade)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Estoque insuficiente'
                ]));
            return;
        }

        $produto = $this->Produto_model->get_by_id($produto_id);
        if (!$produto) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Produto não encontrado'
                ]));
            return;
        }

        $carrinho = $this->session->userdata('carrinho') ?: [];
        $item_key = $produto_id . '_' . $variacao;

        if (isset($carrinho[$item_key])) {
            $carrinho[$item_key]['quantidade'] += $quantidade;
        } else {
            $carrinho[$item_key] = [
                'produto_id' => $produto_id,
                'nome'       => $produto->nome,
                'variacao'   => $variacao,
                'preco'      => (float) $produto->preco,
                'quantidade' => $quantidade
            ];
        }

        $this->session->set_userdata('carrinho', $carrinho);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Produto adicionado ao carrinho'
            ]));
    }

    /**
     * Remove um item específico do carrinho
     *
     * @return void
     */
    public function remover_item_carrinho(): void
    {
        $item_key = trim((string) $this->input->post('item_key'));
        $carrinho = $this->session->userdata('carrinho') ?: [];

        if (isset($carrinho[$item_key])) {
            unset($carrinho[$item_key]);
            $this->session->set_userdata('carrinho', $carrinho);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }

    /**
     * Remove todos os itens do carrinho
     *
     * @return void
     */
    public function remover_carrinho(): void
    {
        if ($this->session->userdata('carrinho')) {
            $this->session->unset_userdata('carrinho');
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }
}
