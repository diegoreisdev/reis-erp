<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto_model extends CI_Model
{
    private $table = 'produtos';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->select('p.*, GROUP_CONCAT(CONCAT(e.variacao, ":", e.quantidade) SEPARATOR "|") as estoque_info');
        $this->db->from('produtos p');
        $this->db->join('estoque e', 'p.id = e.produto_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->group_by('p.id');
        $this->db->order_by('p.nome', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}
