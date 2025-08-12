<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cupom_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get('cupons')->result();
    }

    public function get_by_codigo($codigo)
    {
        $this->db->where('codigo', $codigo);
        $this->db->where('ativo', 1);
        $this->db->where('data_inicio <=', date('Y-m-d'));
        $this->db->where('data_fim >=', date('Y-m-d'));
        return $this->db->get('cupons')->row();
    }

    public function validar_cupom($codigo, $subtotal)
    {
        $cupom = $this->get_by_codigo($codigo);

        if (!$cupom) {
            return array('valido' => false, 'erro' => 'Cupom inválido ou expirado');
        }

        if ($subtotal < $cupom->valor_minimo) {
            return array(
                'valido' => false,
                'erro'   => 'Valor mínimo do pedido deve ser R$ ' . number_format($cupom->valor_minimo, 2, ',', '.')
            );
        }

        $desconto = 0;
        if ($cupom->tipo == 'percentual') {
            $desconto = ($subtotal * $cupom->valor) / 100;
        } else {
            $desconto = $cupom->valor;
        }

        return array(
            'valido'   => true,
            'cupom'    => $cupom,
            'desconto' => $desconto
        );
    }
}
