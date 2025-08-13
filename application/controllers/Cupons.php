<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cupons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cupom_model');
    }

    public function index()
    {
        $data['cupons']     = $this->Cupom_model->get_all();
        $data['page_title'] = 'Cupons';
        $data['scripts']    = ['assets/js/cupom.js'];
        $this->load->view('cupons/index', $data);
    }

    public function get_cupom($id)
    {
        $cupom = $this->Cupom_model->get_by_id($id);
        header('Content-Type: application/json');
        echo json_encode(['cupom' => $cupom]);
    }

    public function salvar()
    {
        $cupom_data = array(
            'codigo'       => strtoupper($this->input->post('codigo')),
            'tipo'         => $this->input->post('tipo'),
            'valor'        => $this->input->post('valor'),
            'valor_minimo' => $this->input->post('valor_minimo'),
            'data_inicio'  => $this->input->post('data_inicio'),
            'data_fim'     => $this->input->post('data_fim'),
            'ativo'        => $this->input->post('ativo') ? 1 : 0
        );

        $cupom_id = $this->input->post('cupom_id');

        if ($cupom_id) {
            $this->Cupom_model->update($cupom_id, $cupom_data);
            $this->session->set_flashdata('sucesso', 'Cupom atualizado com sucesso');
        } else {
            $this->Cupom_model->insert($cupom_data)
                ? $this->session->set_flashdata('sucesso', 'Cupom criado com sucesso')
                :  $this->session->set_flashdata('erro', 'Erro ao criar cupom');
        }

        redirect('cupons');
    }

    public function deletar($id)
    {
        if ($this->Cupom_model->delete($id)) {
            $this->session->set_flashdata('sucesso', 'Cupom deletado com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao deletar cupom!');
        }
        redirect('cupons');
    }
}
