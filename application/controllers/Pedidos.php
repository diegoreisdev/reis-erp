<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Pedido_model', 'Estoque_model']);
    }

    public function finalizar()
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
                $this->Estoque_model->reduzir_estoque($item['produto_id'], $item['variacao'], $item['quantidade']);
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
