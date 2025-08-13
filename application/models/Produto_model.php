<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */
class Produto_model extends CI_Model
{
    /** @var string */
    private $table = 'produtos';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todos os produtos ativos com informações de estoque
     *
     * @return array Lista de produtos
     */
    public function get_all(): array
    {
        $this->db->select('p.*, GROUP_CONCAT(CONCAT(e.variacao, ":", e.quantidade) SEPARATOR "|") as estoque_info');
        $this->db->from('produtos p');
        $this->db->join('estoque e', 'p.id = e.produto_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->group_by('p.id');
        $this->db->order_by('p.nome', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Busca um produto pelo ID
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id(int $id): ?object
    {
        return $this->db->where('id', $id)->get($this->table)->row() ?: null;
    }

    /**
     * Insere um novo produto
     *
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Atualiza um produto pelo ID
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}
