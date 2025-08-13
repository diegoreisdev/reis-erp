<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Estoque_model extends CI_Model
{
    private $table = 'estoque';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_estoque($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_estoque($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function get_estoque($produto_id)
    {
        return $this->db->where('produto_id', $produto_id)->get($this->table)->result();
    }

    public function verificar_estoque($produto_id, $variacao = null, $quantidade = 1)
    {
        $this->db->where('produto_id', $produto_id);

        if ($variacao) $this->db->where('variacao', $variacao);

        $estoque = $this->db->get($this->table)->row();

        return $estoque && $estoque->quantidade >= $quantidade;
    }

    public function reduzir_estoque($produto_id, $variacao, $quantidade)
    {
        $this->db->where('produto_id', $produto_id);
        $this->db->where('variacao', $variacao);
        $this->db->set('quantidade', 'quantidade - ' . (int)$quantidade, FALSE);
        return $this->db->update($this->table);
    }
}
