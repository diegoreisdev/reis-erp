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
        $this->load->view('cupons/index', $data);
    }
}
