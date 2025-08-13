<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('pedidos', ['id' => $id])->row();
    }

    public function insert($data)
    {
        if ($this->db->insert('pedidos', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function insert_item($data)
    {
        return $this->db->insert('pedido_itens', $data);
    }

    public function calcular_frete($subtotal)
    {
        if ($subtotal >= 200.00) {
            return 0.00;
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

    public function gerar_numero_pedido()
    {
        return 'PED' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function get_itens($pedido_id)
    {
        $this->db->select('pi.*, p.nome as produto_nome');
        $this->db->from('pedido_itens pi');
        $this->db->join('produtos p', 'pi.produto_id = p.id');
        $this->db->where('pi.pedido_id', $pedido_id);
        return $this->db->get()->result();
    }
}
