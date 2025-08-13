<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produto_model', 'Cupom_model', 'Pedido_model']);
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

        $url      = "https://viacep.com.br/ws/{$cep}/json/";
        $response = file_get_contents($url);

        header('Content-Type: application/json');
        echo $response;
    }

    public function aplicar_cupom()
    {
        $codigo   = $this->input->post('codigo');
        $subtotal = $this->input->post('subtotal');

        $resultado = $this->Cupom_model->validar_cupom($codigo, $subtotal);

        if ($resultado['valido']) {
            $this->session->set_userdata('cupom_aplicado', $resultado);
        }

        echo json_encode($resultado);
    }

    public function finalizar_pedido()
    {
        $carrinho = $this->session->userdata('carrinho') ?: array();

        if (empty($carrinho)) {
            redirect('produtos');
            return;
        }

        // Calcular totais
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->Pedido_model->calcular_frete($subtotal);

        $desconto       = 0;
        $cupom_codigo   = null;
        $cupom_aplicado = $this->session->userdata('cupom_aplicado');

        if ($cupom_aplicado && $cupom_aplicado['valido']) {
            $desconto     = $cupom_aplicado['desconto'];
            $cupom_codigo = $cupom_aplicado['cupom']->codigo;
        }

        $total = $subtotal - $desconto + $frete;

        // Dados do pedido
        $pedido_data = [
            'numero_pedido'    => $this->Pedido_model->gerar_numero_pedido(),
            'cliente_nome'     => $this->input->post('cliente_nome'),
            'cliente_email'    => $this->input->post('email'),
            'cliente_telefone' => $this->input->post('telefone'),
            'cep'              => $this->input->post('cep'),
            'endereco'         => $this->input->post('endereco_completo'),
            'subtotal'         => $subtotal,
            'desconto'         => $desconto,
            'frete'            => $frete,
            'total'            => $total,
            'cupom_codigo'     => $cupom_codigo
        ];

        $pedido_id = $this->Pedido_model->insert($pedido_data);

        if ($pedido_id) {
            // Inserir itens do pedido e reduzir estoque
            foreach ($carrinho as $item) {
                $item_data = [
                    'pedido_id'      => $pedido_id,
                    'produto_id'     => $item['produto_id'],
                    'variacao'       => $item['variacao'],
                    'quantidade'     => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'subtotal'       => $item['preco'] * $item['quantidade']
                ];

                $this->Pedido_model->insert_item($item_data);
                $this->Produto_model->reduzir_estoque($item['produto_id'], $item['variacao'], $item['quantidade']);
            }


            $this->enviar_email_pedido($pedido_id);

            $this->session->unset_userdata('carrinho');
            $this->session->unset_userdata('cupom_aplicado');

            $this->session->set_flashdata('sucesso', 'Pedido realizado com sucesso! Número: ' . $pedido_data['numero_pedido']);
        }

        redirect('produtos');
    }

    private function enviar_email_pedido($pedido_id)
    {
        $pedido        = $this->Pedido_model->get_by_id($pedido_id);
        $pedido->itens = $this->Pedido_model->get_itens($pedido_id);

        if (empty($_SERVER['SERVER_NAME'])) {
            $_SERVER['SERVER_NAME'] = !empty($_SERVER['HTTP_HOST']) ? explode(':', $_SERVER['HTTP_HOST'])[0] : 'localhost';
        }

        $config = array(
            'protocol'     => 'smtp',
            'smtp_host'    => 'sandbox.smtp.mailtrap.io',
            'smtp_port'    => 2525,
            'smtp_user'    => 'b2b5583474e935',
            'smtp_pass'    => '4e9e1dbace63ee',
            'smtp_crypto'  => 'tls',
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'validate'     => FALSE,
            'crlf'         => "\r\n",
            'newline'      => "\r\n",
            'smtp_timeout' => 30
        );

        $this->email->initialize($config);

        $data['pedido'] = $pedido;

        $mensagem = $this->load->view('emails/pedido_confirmacao', $data, TRUE);

        $this->email->from('no-reply@reiserp.com', 'Reis ERP');
        $this->email->to($pedido->cliente_email);
        $this->email->subject('Confirmação do Pedido #' . $pedido->numero_pedido);
        $this->email->message($mensagem);

        if ($this->email->send()) {
            return TRUE;
        } else {
            log_message('error', 'Erro ao enviar email: ' . $this->email->print_debugger());
            return FALSE;
        }
    }
}
