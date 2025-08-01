-- Criação do banco de dados
CREATE DATABASE reis-erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reis-erp;

-- Tabela produtos
CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela estoque
CREATE TABLE estoque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    produto_id INT NOT NULL,
    variacao VARCHAR(255) DEFAULT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Tabela cupons
CREATE TABLE cupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    tipo ENUM('percentual', 'fixo') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    valor_minimo DECIMAL(10,2) DEFAULT 0.00,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela pedidos
CREATE TABLE pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    cliente_nome VARCHAR(255) NOT NULL,
    cliente_email VARCHAR(255) NOT NULL,
    cliente_telefone VARCHAR(20),
    cep VARCHAR(10) NOT NULL,
    endereco TEXT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    desconto DECIMAL(10,2) DEFAULT 0.00,
    frete DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    cupom_codigo VARCHAR(50) NULL,
    status ENUM('pendente', 'confirmado', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cupom_codigo) REFERENCES cupons(codigo) ON DELETE SET NULL
);

-- Tabela itens do pedido
CREATE TABLE pedido_itens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    variacao VARCHAR(255),
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Índices para performance
ALTER TABLE estoque ADD INDEX idx_produto_variacao (produto_id, variacao);
ALTER TABLE cupons ADD INDEX idx_codigo_ativo (codigo, ativo);

-- Inserir cupons de exemplo
INSERT INTO cupons (codigo, tipo, valor, valor_minimo, data_inicio, data_fim) VALUES
('DESC10', 'percentual', 10.00, 50.00, '2024-01-01', '2025-12-31'),
('FRETE15', 'fixo', 15.00, 100.00, '2024-01-01', '2025-12-31'),
('PROMO20', 'percentual', 20.00, 200.00, '2024-01-01', '2025-12-31');

-- Inserir produtos de exemplo
INSERT INTO produtos (nome, preco, descricao) VALUES
('Camiseta Básica', 49.90, 'Camiseta 100% algodão'),
('Calça Jeans', 120.00, 'Calça jeans tradicional'),
('Tênis Esportivo', 180.00, 'Tênis para corrida e caminhada');

-- Inserir estoque de exemplo
INSERT INTO estoque (produto_id, variacao, quantidade) VALUES
(1, 'P', 10),
(1, 'M', 15),
(1, 'G', 8),
(2, '38', 5),
(2, '40', 7),
(2, '42', 3),
(3, '39', 4),
(3, '40', 6),
(3, '41', 2);