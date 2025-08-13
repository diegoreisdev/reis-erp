<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */
class Cupom_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retorna todos os cupons
     *
     * @return array
     */
    public function get_all(): array
    {
        return $this->db->get('cupons')->result();
    }

    /**
     * Busca cupom pelo ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id(int $id): ?object
    {
        return $this->db->where('id', $id)->get('cupons')->row() ?: null;
    }

    /**
     * Busca cupom ativo pelo código e data de validade
     *
     * @param string $codigo
     * @return object|null
     */
    public function get_by_codigo(string $codigo): ?object
    {
        $this->db->where('codigo', $codigo);
        $this->db->where('ativo', 1);
        $this->db->where('data_inicio <=', date('Y-m-d'));
        $this->db->where('data_fim >=', date('Y-m-d'));

        return $this->db->get('cupons')->row() ?: null;
    }

    /**
     * Valida um cupom e calcula o desconto
     *
     * @param string $codigo
     * @param float $subtotal
     * @return array
     */
    public function validar_cupom(string $codigo, float $subtotal): array
    {
        $cupom = $this->get_by_codigo($codigo);

        if (!$cupom) {
            return [
                'valido' => false,
                'erro'   => 'Cupom inválido ou expirado'
            ];
        }

        if ($subtotal < $cupom->valor_minimo) {
            return [
                'valido' => false,
                'erro'   => 'Valor mínimo do pedido deve ser R$ ' . number_format($cupom->valor_minimo, 2, ',', '.')
            ];
        }

        $desconto = ($cupom->tipo === 'percentual')
            ? ($subtotal * $cupom->valor) / 100
            : $cupom->valor;

        return [
            'valido'   => true,
            'cupom'    => $cupom,
            'desconto' => $desconto
        ];
    }

    /**
     * Insere um cupom
     *
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->db->insert('cupons', $data);
    }

    /**
     * Atualiza um cupom pelo ID
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update('cupons', $data);
    }

    /**
     * Deleta um cupom pelo ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db->where('id', $id)->delete('cupons');
    }
}
