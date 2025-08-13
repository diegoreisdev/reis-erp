<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Cupom_model $Cupom_model
 * @property CI_Session  $session
 * @property CI_Input    $input
 */
class Cupons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cupom_model');
    }

    /**
     * Lista todos os cupons
     *
     * @return void
     */
    public function index(): void
    {
        $data = [
            'cupons'     => $this->Cupom_model->get_all(),
            'page_title' => 'Cupons',
            'scripts'    => ['assets/js/cupom.js']
        ];

        $this->load->view('cupons/index', $data);
    }

    /**
     * Retorna os dados de um cupom pelo ID
     *
     * @param int $id
     * @return void
     */
    public function get_cupom(int $id): void
    {
        $cupom = $this->Cupom_model->get_by_id($id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['cupom' => $cupom]));
    }

    /**
     * Cria ou atualiza um cupom
     *
     * @return void
     */
    public function salvar(): void
    {
        $cupom_data = [
            'codigo'       => strtoupper(trim($this->input->post('codigo'))),
            'tipo'         => $this->input->post('tipo'),
            'valor'        => (float) $this->input->post('valor'),
            'valor_minimo' => (float) $this->input->post('valor_minimo'),
            'data_inicio'  => $this->input->post('data_inicio'),
            'data_fim'     => $this->input->post('data_fim'),
            'ativo'        => $this->input->post('ativo') ? 1 : 0
        ];

        $cupom_id = (int) $this->input->post('cupom_id');

        if ($cupom_id > 0) {
            $this->Cupom_model->update($cupom_id, $cupom_data);
            $this->session->set_flashdata('sucesso', 'Cupom atualizado com sucesso');
        } else {
            if ($this->Cupom_model->insert($cupom_data)) {
                $this->session->set_flashdata('sucesso', 'Cupom criado com sucesso');
            } else {
                $this->session->set_flashdata('erro', 'Erro ao criar cupom');
            }
        }

        redirect('cupons');
    }

    /**
     * Exclui um cupom pelo ID
     *
     * @param int $id
     * @return void
     */
    public function deletar(int $id): void
    {
        if ($this->Cupom_model->delete($id)) {
            $this->session->set_flashdata('sucesso', 'Cupom deletado com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao deletar cupom!');
        }

        redirect('cupons');
    }

    /**
     * Valida e aplica cupom na sessão
     *
     * @return void
     */
    public function aplicar_cupom(): void
    {
        $codigo   = $this->input->post('codigo');
        $subtotal = (float) $this->input->post('subtotal');

        $resultado = $this->Cupom_model->validar_cupom($codigo, $subtotal);

        if (!empty($resultado['valido']) && $resultado['valido']) {
            $this->session->set_userdata('cupom_aplicado', $resultado);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
}
