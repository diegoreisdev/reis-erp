<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */
class Estoque_model extends CI_Model
{
    /** @var string */
    private $table = 'estoque';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insere um registro no estoque
     *
     * @param array $data
     * @return bool
     */
    public function insert_estoque(array $data): bool
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Atualiza um registro de estoque pelo ID
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_estoque(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Retorna todo o estoque de um produto
     *
     * @param int $produto_id
     * @return array
     */
    public function get_estoque(int $produto_id): array
    {
        return $this->db->where('produto_id', $produto_id)->get($this->table)->result();
    }

    /**
     * Verifica se há estoque suficiente para um produto/variação
     *
     * @param int $produto_id
     * @param string|null $variacao
     * @param int $quantidade
     * @return bool
     */
    public function verificar_estoque(int $produto_id, ?string $variacao = null, int $quantidade = 1): bool
    {
        $this->db->where('produto_id', $produto_id);

        if ($variacao !== null) {
            $this->db->where('variacao', $variacao);
        }

        $estoque = $this->db->get($this->table)->row();

        return $estoque && $estoque->quantidade >= $quantidade;
    }

    /**
     * Reduz a quantidade em estoque para um produto/variação
     *
     * @param int $produto_id
     * @param string $variacao
     * @param int $quantidade
     * @return bool
     */
    public function reduzir_estoque(int $produto_id, string $variacao, int $quantidade): bool
    {
        $this->db->where('produto_id', $produto_id);
        $this->db->where('variacao', $variacao);
        $this->db->set('quantidade', 'quantidade - ' . (int) $quantidade, false);
        return $this->db->update($this->table);
    }

    /**
     * Exclui todo o estoque de um produto específico
     *
     * @param int $produto_id
     * @return bool
     */
    public function excluir_estoque_produto(int $produto_id): bool
    {
        return $this->db->where('produto_id', $produto_id)->delete($this->table);
    }
}
