<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto_model extends CI_Model
{
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
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('produtos')->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('produtos', $data);
    }

    public function insert_estoque($data)
    {
        return $this->db->insert('estoque', $data);
    }

    public function update_estoque($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('estoque', $data);
    }

    public function get_estoque($produto_id)
    {
        $this->db->where('produto_id', $produto_id);
        return $this->db->get('estoque')->result();
    }

    public function verificar_estoque($produto_id, $variacao = null, $quantidade = 1)
    {
        $this->db->where('produto_id', $produto_id);

        if ($variacao) $this->db->where('variacao', $variacao);

        $estoque = $this->db->get('estoque')->row();

        return $estoque && $estoque->quantidade >= $quantidade;
    }
}
