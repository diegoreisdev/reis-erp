<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */
class Pedido_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca um pedido pelo ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id(int $id): ?object
    {
        return $this->db->get_where('pedidos', ['id' => $id])->row() ?: null;
    }

    /**
     * Insere um pedido
     *
     * @param array $data
     * @return int|false ID inserido ou false em caso de erro
     */
    public function insert(array $data)
    {
        if ($this->db->insert('pedidos', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Insere um item no pedido
     *
     * @param array $data
     * @return bool
     */
    public function insert_item(array $data): bool
    {
        return $this->db->insert('pedido_itens', $data);
    }

    /**
     * Calcula o valor do frete com base no subtotal
     *
     * @param float $subtotal
     * @return float
     */
    public function calcular_frete(float $subtotal): float
    {
        if ($subtotal >= 200.00) {
            return 0.00;
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        }
        return 20.00;
    }

    /**
     * Gera um número de pedido único
     *
     * @return string
     */
    public function gerar_numero_pedido(): string
    {
        return 'PED' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Busca todos os itens de um pedido
     *
     * @param int $pedido_id
     * @return array
     */
    public function get_itens(int $pedido_id): array
    {
        $this->db->select('pi.*, p.nome as produto_nome');
        $this->db->from('pedido_itens pi');
        $this->db->join('produtos p', 'pi.produto_id = p.id');
        $this->db->where('pi.pedido_id', $pedido_id);
        return $this->db->get()->result();
    }
}
