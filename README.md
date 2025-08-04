# SMARTSTOCK - Controle de Estoque Inteligente

## 📋 Índice
- [Sobre o Projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Como Usar](#como-usar)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [API/Backend](#apibackend)
- [Frontend](#frontend)
- [Banco de Dados](#banco-de-dados)
- [Troubleshooting](#troubleshooting)
- [Contribuição](#contribuição)
- [Licença](#licença)

## 🎯 Sobre o Projeto

O **SMARTSTOCK** é um sistema completo de controle de estoque inteligente desenvolvido em PHP, projetado para gerenciar produtos, ordens de serviço e fornecer suporte técnico. O sistema oferece uma interface intuitiva e responsiva para controle eficiente de estoque com funcionalidades avançadas de autenticação e gestão de usuários.

### Objetivos
- Controle centralizado de estoque
- Gestão de ordens de serviço
- Sistema de autenticação seguro
- Interface responsiva e moderna
- Relatórios e alertas em tempo real

### Público-Alvo
- Empresas que necessitam controle de estoque
- Equipes de manutenção
- Administradores de sistemas

## ⚡ Funcionalidades

### 🔐 Autenticação
- ✅ Login seguro com validação em tempo real
- ✅ Sistema de recuperação de senha via email
- ✅ Controle de sessão com proteção
- ✅ Cadastro de novos usuários
- ✅ Alteração de senha

### 📊 Dashboard
- ✅ Visão geral do estoque
- ✅ Perfil do usuário com foto
- ✅ Navegação intuitiva
- ✅ Acesso rápido às funcionalidades

### 📦 Gestão de Estoque
- ✅ Cadastro de produtos
- ✅ Controle de status (Estoque, Manutenção, Em uso)
- ✅ Gestão de quantidades
- ✅ Visualização por categorias
- ✅ Edição e exclusão de produtos

### 🔧 Ordens de Serviço
- ✅ Criação de ordens de serviço
- ✅ Sistema de checklist
- ✅ Controle de estoque por ordem
- ✅ Acompanhamento de status

### 📞 Suporte
- ✅ Sistema de tickets de suporte
- ✅ Upload de arquivos
- ✅ Comunicação direta

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 8.x** - Linguagem principal do servidor
- **MySQL 8.0** - Banco de dados relacional
- **PDO** - Camada de abstração do banco de dados
- **Sessions** - Controle de autenticação

### Frontend
- **HTML5/CSS3** - Estrutura e estilização
- **JavaScript ES6+** - Interatividade e validações
- **Font Awesome** - Ícones
- **SweetAlert2** - Alertas modernos
- **Responsive Design** - Layout adaptativo

### Ferramentas de Desenvolvimento
- **Laragon** - Ambiente de desenvolvimento local
- **Git** - Controle de versão
- **Cursor AI** - Editor com inteligência artificial

## 📋 Pré-requisitos

```bash
- PHP >= 8.0
- MySQL >= 8.0
- Apache/Nginx
- Laragon (recomendado para desenvolvimento)
- Extensões PHP: PDO, PDO_MySQL, mbstring
```

## 🚀 Instalação

### 1. Clone o Repositório
```bash
git clone https://github.com/rosamrcl/smartstock
cd smartstock
```

### 2. Configuração do Ambiente
```bash
# Certifique-se que o Laragon está configurado
# O projeto deve estar em: C:\laragon\www\smartstock\
```

### 3. Banco de Dados
```sql
-- Importe o arquivo SQL
mysql -u root -p smartstock < Database/database.sql
```

### 4. Configuração do Servidor
- Configure o DocumentRoot para a pasta do projeto
- Certifique-se que mod_rewrite está habilitado
- Verifique as permissões de pasta

## ⚙️ Configuração

### Banco de Dados (Backend/conexao.php)
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'smartstock';
```

### Configurações Gerais
- Base URL: `http://localhost/smartstock/`
- Nome do Sistema: `SMARTSTOCK`
- Timezone: Configurado automaticamente

## 📖 Como Usar

### 1. Primeiro Acesso
1. Acesse `http://localhost/smartstock/`
2. Será redirecionado para a tela de login
3. Use as credenciais padrão ou crie uma conta

### 2. Login no Sistema
- **URL**: `/Frontend/login.php`
- **Campos**: Email e Senha
- **Recursos**: Validação em tempo real, Recuperação de senha

### 3. Dashboard Principal
- Visão geral do estoque atual
- Acesso rápido às funcionalidades
- Perfil do usuário
- Navegação intuitiva

### 4. Gestão de Produtos
- Cadastrar novos produtos
- Editar informações existentes
- Definir status (Estoque, Manutenção, Em uso)
- Controlar quantidades

### 5. Ordens de Serviço
- Criar novas ordens
- Gerenciar checklists
- Controlar estoque por ordem
- Acompanhar status

## 📁 Estrutura do Projeto

```
smartstock/
│
├── 📄 index.php                    # Redirecionamento inicial
│
├── 📁 Backend/                     # Processamento e lógica
│   ├── conexao.php                # Conexão com banco de dados
│   ├── login.php                  # Processamento de login
│   ├── logout.php                 # Processamento de logout
│   ├── cadastro.php               # Processamento de cadastro
│   ├── produtos.php               # CRUD de produtos
│   ├── alterar_senha.php          # Alteração de senha
│   ├── atualizar.php              # Atualização de dados
│   ├── salvar_checklist.php       # Salvar progresso do checklist
│   ├── carregar_checklist.php     # Carregar dados do checklist
│   ├── solicitar_redefinicao.php  # Solicitar redefinição de senha
│   ├── process_forgot_password.php # Processar esqueci senha
│   ├── process_reset_password.php  # Processar reset de senha
│   └── includes/
│       └── security.php           # Funções de segurança
│
├── 📁 Frontend/                    # Interface do usuário
│   ├── home.php                   # Dashboard principal
│   ├── login.php                  # Tela de login
│   ├── cadastro.php               # Tela de cadastro
│   ├── gerenciarprodutos.php      # Gestão de produtos
│   ├── listar_ordens.php          # Listar ordens de serviço
│   ├── suporte.php                # Sistema de suporte
│   ├── updateperfil.php           # Atualização de perfil
│   ├── alterar_senha.php          # Alteração de senha
│   ├── esqueci_senha.php          # Recuperação de senha
│   ├── redefinir_senha.php        # Redefinição de senha
│   ├── about.php                  # Sobre o sistema
│   ├── includes/                  # Componentes reutilizáveis
│   │   ├── head.php              # Meta tags e CSS
│   │   ├── header.php            # Cabeçalho autenticado
│   │   ├── headerog.php          # Cabeçalho não autenticado
│   │   ├── footer.php            # Rodapé
│   │   ├── alerts.php            # Sistema de alertas
│   │   ├── ux.php                # Melhorias de UX
│   │   └── validation.php        # Validações
│   ├── ressources/               # Recursos estáticos
│   │   ├── css/                  # Estilos
│   │   ├── js/                   # JavaScript
│   │   └── img/                  # Imagens
│   └── uploads/                  # Arquivos enviados
│
├── 📁 Database/                   # Banco de dados
│   ├── database.sql              # Schema completo
│   ├── index.php                 # Página de teste
│   └── password_reset_tokens.sql # Tokens de recuperação
│
└── 📄 README.md                   # Documentação
```

## 🔧 API/Backend

### Endpoints Principais
```php
POST /Backend/login.php                    # Autenticação
POST /Backend/cadastro.php                 # Cadastro de usuário
POST /Backend/produtos.php                 # CRUD de produtos
POST /Backend/alterar_senha.php            # Alteração de senha
POST /Backend/atualizar.php                # Atualização de dados
POST /Backend/salvar_checklist.php         # Salvar progresso do checklist
GET  /Backend/carregar_checklist.php       # Carregar dados do checklist
POST /Backend/solicitar_redefinicao.php    # Solicitar redefinição
POST /Backend/process_forgot_password.php  # Processar esqueci senha
POST /Backend/process_reset_password.php   # Processar reset
```

### Funções Principais
```php
// Conexão com banco
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

// Verificação de sessão
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Sanitização de dados
htmlspecialchars($data)

// Validação de email
filter_var($email, FILTER_VALIDATE_EMAIL)
```

## 🎨 Frontend

### Páginas Principais
- **login.php** - Tela de autenticação
- **home.php** - Dashboard principal
- **gerenciarprodutos.php** - Gestão de produtos
- **listar_ordens.php** - Listar ordens de serviço
- **suporte.php** - Sistema de suporte

### Componentes CSS
- **Responsive design** - Layout adaptativo
- **Cards** - Para informações
- **Formulários estilizados** - Validação visual
- **Alertas visuais** - SweetAlert2
- **Header dinâmico** - Scroll effects

### JavaScript
- **Validação em tempo real** - Formulários
- **Alertas interativos** - SweetAlert2
- **Header scroll** - Efeitos visuais
- **UX improvements** - Melhorias de experiência

## 🗄️ Banco de Dados

### Tabelas Principais
```sql
usuarios                    # Usuários do sistema
produtos                   # Produtos cadastrados
ordens_servico            # Ordens de serviço
checklist                 # Itens de checklist
ordem_estoque             # Estoque por ordem
suporte                   # Tickets de suporte
redefinicao_senha         # Tokens de recuperação
password_reset_tokens     # Tokens de reset (Backend)
```

### Schema de Exemplo
```sql
CREATE TABLE usuarios (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    sobrenome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    foto_perfil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE produtos (
    id_products INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    descricao TEXT,
    status ENUM('Estoque','Manutenção','Em uso'),
    quantidade INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

## 🔍 Troubleshooting

### Problemas Comuns

#### Erro: foreach() argument must be of type array
```php
// Solução: Validar tipo antes do foreach
if (is_array($data)) {
    foreach($data as $item) { ... }
}
```

#### Erro de Conexão com Banco
- Verificar credenciais em `Backend/conexao.php`
- Confirmar se MySQL está rodando
- Verificar se database existe
- Testar conexão manualmente

#### CSS/JS não carregam
- Verificar caminhos em `Frontend/ressources/`
- Confirmar permissões de arquivo
- Verificar configuração do servidor
- Limpar cache do navegador

#### Problemas de Sessão
- Verificar se `session_start()` está sendo chamado
- Confirmar configurações do PHP
- Verificar permissões de pasta temporária

#### Upload de Arquivos
- Verificar permissões da pasta `Frontend/uploads/`
- Confirmar configuração `upload_max_filesize` no PHP
- Verificar `post_max_size` no php.ini

## 🤝 Contribuição

1. **Fork o projeto**
2. **Crie uma branch** (`git checkout -b feature/nova-funcionalidade`)
3. **Commit suas mudanças** (`git commit -am 'Add nova funcionalidade'`)
4. **Push para a branch** (`git push origin feature/nova-funcionalidade`)
5. **Abra um Pull Request**

### Padrões de Código
- Use PSR-4 para autoloading
- Siga PSR-12 para estilo de código
- Documente funções complexas
- Mantenha consistência na nomenclatura

## 📝 Licença

Este projeto está sob a licença da empresa LARIM. Veja o arquivo `LICENSE` para mais detalhes.

---

## 📞 Suporte

Para suporte técnico ou dúvidas sobre o sistema:
- **Email**: suporte@smartstock.com
- **Documentação**: EM PROCESSO

---

**Desenvolvido com ❤️ pela equipe LARIM** 