# 🚀 Reis ERP - Sistema de Gestão Empresarial

[![PHP](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://php.net)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-orange.svg)](https://codeigniter.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://mysql.com)
[![Redis](https://img.shields.io/badge/Redis-Latest-red.svg)](https://redis.io)
[![Nginx](https://img.shields.io/badge/Nginx-1.25.1-green.svg)](https://nginx.org)
[![Docker](https://img.shields.io/badge/docker compose-blue.svg)](https://docker.com)

Sistema ERP completo desenvolvido em **CodeIgniter 3** com arquitetura moderna e containerizada, focado em gestão de produtos, estoque, pedidos e cupons de desconto.

## 👨‍💻 Desenvolvedor

**Diego Reis** - [@diegoreisdev](https://github.com/diegoreisdev)

## 🏗️ Arquitetura do Sistema

### **Backend**

- **Framework**: CodeIgniter 3.1.13
- **PHP**: 8.1-FPM com extensões otimizadas
- **Banco de Dados**: MySQL 8.0
- **Cache**: Redis (Latest)
- **Servidor Web**: Nginx 1.25.1 Alpine

### **Frontend**

- **JavaScript**: Vanilla JS com módulos organizados
- **CSS**: Estilos customizados responsivos
- **Interface**: Design moderno e intuitivo

### **Infraestrutura**

- **Containerização**: Docker + Docker Compose
- **Orquestração**: Multi-container com redes isoladas
- **Volumes**: Persistência de dados MySQL e Redis
- **Portas**: Configuração otimizada para desenvolvimento

## 🚀 Funcionalidades Principais

### **Gestão de Produtos**

- ✅ Cadastro e edição de produtos
- ✅ Sistema de variações (tamanhos, cores, etc.)
- ✅ Controle de preços e descrições
- ✅ Status ativo/inativo

### **Controle de Estoque**

- ✅ Gestão de quantidades por variação
- ✅ Controle de entrada e saída
- ✅ Relatórios de disponibilidade
- ✅ Integração com produtos

### **Sistema de Cupons**

- ✅ Cupons percentuais e fixos
- ✅ Validação de valor mínimo
- ✅ Controle de datas de validade
- ✅ Status ativo/inativo

### **Gestão de Pedidos**

- ✅ Processo completo de checkout
- ✅ Carrinho de compras
- ✅ Aplicação de cupons
- ✅ Cálculo de frete
- ✅ Status de acompanhamento

### **Carrinho de Compras**

- ✅ Adição/remoção de produtos
- ✅ Cálculo automático de totais
- ✅ Aplicação de cupons
- ✅ Persistência de sessão

## 🛠️ Tecnologias e Versões

### **Container Principal (App)**

- **PHP**: 8.1-FPM
- **Extensões**: PDO MySQL, MySQLi, ZIP, GD, MBString, XML
- **Node.js**: LTS (Latest)
- **Composer**: Latest
- **Permissões**: Otimizadas para CodeIgniter 3

### **Banco de Dados**

- **MySQL**: 8.0
- **Autenticação**: Native Password
- **Porta**: 3306
- **Volumes**: Persistente

### **Cache e Sessões**

- **Redis**: Latest
- **Porta**: 6379
- **Volumes**: Persistente

### **Servidor Web**

- **Nginx**: 1.25.1 Alpine Slim
- **Porta**: 8000
- **Configuração**: Otimizada para CodeIgniter 3

### **Ferramentas de Desenvolvimento**

- **phpMyAdmin**: Latest
- **Porta**: 8080
- **Acesso**: Root com senha configurável

## 📋 Pré-requisitos

- **Docker**: 28.2.2+
- **Docker Compose**: 2.0+
- **Git**: Para clonar o repositório
- **Portas disponíveis**: 8000, 8080, 3306, 6379

## 🚀 Instalação e Configuração

### **1. Clone o Repositório**

```bash
git clone https://github.com/diegoreisdev/reis-erp.git
cd reis-erp
```

### 2. Suba os Containers

```bash
docker compose up -d
```

### 3. Configure o CodeIgniter

Ajuste o arquivo `application/config/database.php`:

```php
$db['default'] = array(
	'dsn'      => '',
	'hostname' => 'db',
	'username' => 'reis-erp',
	'password' => 'reis-erp',
	'database' => 'reis-erp',
	'dbdriver' => 'mysqli',
      // ... resto da configuração
);
```

### **4. Configuração do Banco de Dados**

```bash
# Acessar o container do banco
docker compose exec db mysql -u root -p

# Ou usar o phpMyAdmin em: http://localhost:8080
# Usuário: reis-erp
# Senha: reis-rp
````

### **5. Importar Estrutura do Banco**

```bash
# O arquivo base.sql será executado automaticamente
# Ou execute manualmente via phpMyAdmin
```

### **6. Configurações do CodeIgniter**

```bash
# Ajustar permissões (se necessário)
docker compose exec app chmod -R 777 application/cache
docker compose exec app chmod -R 777 application/logs
```

### **7. Configurações para envio do e-mail**
Ajuste o arquivo `application/controllers/Pedidos.php`:
`enviar_email_pedido`
- Utilizei o [Mailtrap](https://mailtrap.io/) para testes
```bash
# Ajustar permissões
$config = [
	'protocol'     => 'smtp',
	'smtp_host'    => 'sandbox.smtp.mailtrap.io',
	'smtp_port'    => 2525,
	'smtp_user'    => 'seu-user',
	'smtp_pass'    => 'seu-pass',
	'smtp_crypto'  => 'tls',
	'mailtype'     => 'html',
	'charset'      => 'utf-8',
	'validate'     => false,
	'crlf'         => "\r\n",
	'newline'      => "\r\n",
	'smtp_timeout' => 30
];
```

## 🌐 Acesso ao Sistema

- **Aplicação Principal**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **Banco MySQL**: localhost:3306
- **Redis**: localhost:6379

## 📁 Estrutura do Projeto

```
reis-erp/
├── application/               # Código da aplicação CodeIgniter
│   ├── controllers/           # Controladores MVC
│   │   ├── Produtos.php       # Gestão de produtos
│   │   ├── Estoque.php        # Controle de estoque
│   │   ├── Carrinho.php       # Carrinho de compras
│   │   ├── Cupons.php         # Sistema de cupons
│   │   └── Pedidos.php        # Gestão de pedidos
│   ├── models/                # Modelos de dados
│   ├── views/                 # Templates de visualização
│   └── config/                # Configurações
├── assets/                    # Recursos estáticos
│   ├── css/                   # Estilos CSS
│   └── js/                    # JavaScript modular
├── docker/                    # Configurações Docker
│   ├── php/                   # Container PHP
│   ├── nginx/                 # Servidor web
│   └── redis/                 # Cache Redis
├── system/                    # Core do CodeIgniter
├── docker compose.yml         # Orquestração Docker
├── base.sql                   # Estrutura do banco
└── README.md                  # Este arquivo
```

## 🔧 Comandos Úteis

### **Gerenciamento de Containers**

```bash
# Iniciar serviços
docker compose up -d

# Parar serviços
docker compose down

# Reconstruir containers
docker compose up -d --build

# Ver logs
docker compose logs -f [servico]

# Acessar container
docker compose exec [servico] bash
```

### **Manutenção do Sistema**

```bash
# Limpar cache
docker compose exec app rm -rf application/cache/*

# Verificar permissões
docker compose exec app ls -la application/

# Backup do banco
docker compose exec db mysqldump -u root -p reis-erp > backup.sql
```

## 🎯 Pontos Fortes do Sistema

### **Arquitetura Robusta**

- ✅ **MVC bem estruturado** com separação clara de responsabilidades
- ✅ **Sistema de rotas** otimizado para SEO
- ✅ **Validação de dados** em múltiplas camadas
- ✅ **Tratamento de erros** consistente

### **Performance e Escalabilidade**

- ✅ **Cache Redis** para sessões e dados frequentes
- ✅ **Índices otimizados** no banco de dados
- ✅ **Configurações PHP** ajustadas para produção
- ✅ **Nginx** configurado para alta performance

### **Segurança**

- ✅ **Proteção contra acesso direto** aos arquivos do sistema
- ✅ **Validação de entrada** em todos os formulários
- ✅ **Sanitização de dados** antes do banco
- ✅ **Controle de sessões** seguro

### **Desenvolvimento**

- ✅ **Containerização completa** para ambiente consistente
- ✅ **Hot-reload** para desenvolvimento ágil
- ✅ **Logs estruturados** para debugging
- ✅ **Versionamento** com Git

## 🐛 Solução de Problemas

### **Problemas Comuns**

#### **Container não inicia**

```bash
# Verificar logs
docker compose logs [servico]

# Verificar portas em uso
netstat -tulpn | grep :8000
```

#### **Erro de permissões**

```bash
# Corrigir permissões
docker compose exec app chown -R www-data:www-data /var/www
docker compose exec app chmod -R 755 /var/www
```

#### **Banco não conecta**

```bash
# Verificar status do MySQL
docker compose exec db mysqladmin ping

# Verificar variáveis de ambiente
docker compose exec db env | grep MYSQL
```

## 📞 Suporte

- **Issues**: [GitHub Issues](https://github.com/diegoreisdev/reis-erp/issues)
- **Documentação**: Este README e comentários no código
- **Desenvolvedor**: Diego Reis (@diegoreisdev)

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 🙏 Agradecimentos

- **CodeIgniter Team** pelo framework robusto
- **Comunidade Docker** pelas ferramentas de containerização

---

**Desenvolvido com ❤️ por Diego Reis**

_Última atualização: Agosto 2025_
