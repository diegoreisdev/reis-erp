<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Pedido_model  $Pedido_model
 * @property Estoque_model $Estoque_model
 * @property CI_Session    $session
 * @property CI_Input      $input
 * @property CI_Email      $email
 */
class Pedidos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Pedido_model', 'Estoque_model']);
        $this->load->library('email');
    }

    /**
     * Finaliza o pedido, insere no banco e envia e-mail
     *
     * @return void
     */
    public function finalizar(): void
    {
        $carrinho = $this->session->userdata('carrinho') ?: [];

        if (empty($carrinho)) {
            redirect('produtos');
            return;
        }

        // Calcular subtotal
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Calcular frete
        $frete = $this->Pedido_model->calcular_frete($subtotal);

        // Verificar cupom
        $desconto     = 0;
        $cupom_codigo = null;
        $cupom        = $this->session->userdata('cupom_aplicado');

        if ($cupom && $cupom['valido']) {
            $desconto     = $cupom['desconto'];
            $cupom_codigo = $cupom['cupom']->codigo;
        }

        $total = $subtotal - $desconto + $frete;

        // Montar dados do pedido
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
            // Inserir itens e atualizar estoque
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

            // Enviar confirmação por e-mail
            $this->enviar_email_pedido($pedido_id);

            // Limpar sessão
            $this->session->unset_userdata(['carrinho', 'cupom_aplicado']);

            $this->session->set_flashdata('sucesso', 'Pedido realizado com sucesso! Número: ' . $pedido_data['numero_pedido']);
        }

        redirect('produtos');
    }

    /**
     * Envia e-mail de confirmação do pedido
     *
     * @param int $pedido_id
     * @return bool
     */
    private function enviar_email_pedido(int $pedido_id): bool
    {
        $pedido        = $this->Pedido_model->get_by_id($pedido_id);
        $pedido->itens = $this->Pedido_model->get_itens($pedido_id);

        // Garantir SERVER_NAME para ambientes CLI
        if (empty($_SERVER['SERVER_NAME'])) {
            $_SERVER['SERVER_NAME'] = !empty($_SERVER['HTTP_HOST'])
                ? explode(':', $_SERVER['HTTP_HOST'])[0]
                :  'localhost';
        }

        $config = [
            'protocol'     => 'smtp',
            'smtp_host'    => 'sandbox.smtp.mailtrap.io',
            'smtp_port'    => 2525,
            'smtp_user'    => 'b2b5583474e935',
            'smtp_pass'    => '4e9e1dbace63ee',
            'smtp_crypto'  => 'tls',
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'validate'     => false,
            'crlf'         => "\r\n",
            'newline'      => "\r\n",
            'smtp_timeout' => 30
        ];

        $this->email->initialize($config);

        $data['pedido'] = $pedido;
        $mensagem       = $this->load->view('emails/pedido_confirmacao', $data, true);

        $this->email->from('no-reply@reiserp.com', 'Reis ERP');
        $this->email->to($pedido->cliente_email);
        $this->email->subject('Confirmação do Pedido #' . $pedido->numero_pedido);
        $this->email->message($mensagem);

        if ($this->email->send()) {
            return true;
        }

        log_message('error', 'Erro ao enviar email: ' . $this->email->print_debugger());
        return false;
    }
}
