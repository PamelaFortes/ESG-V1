# SafeTrack

## Sistema de Controle de Treinamentos e Segurança do Trabalhador

O **SafeTrack** é um sistema desenvolvido como projeto acadêmico do **ESG de Mato Grosso**, com foco no pilar **Social (S)** e apoio ao pilar de **Governança (G)**.

A proposta é centralizar o controle de treinamentos obrigatórios e informações relacionadas à segurança dos trabalhadores, permitindo acompanhar funcionários, treinamentos realizados, validade dos treinamentos e pendências.

---

## Objetivo

O sistema tem como objetivo facilitar o gerenciamento dos treinamentos de segurança dos colaboradores, permitindo que gestores acompanhem:

- Funcionários cadastrados;
- Treinamentos disponíveis;
- Registros de treinamentos realizados;
- Datas de realização e validade;
- Treinamentos próximos do vencimento;
- Treinamentos vencidos;
- Indicadores gerais por meio do dashboard.

---

## Funcionalidades

### Autenticação

- Login de usuários;
- Validação de senha;
- Autenticação por sessão;
- Diferenciação entre `gestor` e `funcionario`;
- Redirecionamento automático conforme o perfil;
- Logout;
- Proteção das páginas para usuários não autenticados;
- Restrição de acesso conforme o tipo de usuário.

### Funcionários

- Cadastro de funcionários;
- Listagem de funcionários;
- Visualização do perfil do funcionário;
- Atualização de dados;
- Validação de CPF duplicado;
- Controle de status do funcionário.

### Treinamentos

- Listagem de treinamentos;
- Visualização das informações dos treinamentos;
- Registro da realização de treinamentos;
- Controle de validade dos treinamentos;
- Identificação de treinamentos válidos, próximos do vencimento e vencidos.

### Dashboard

O dashboard apresenta indicadores do sistema, incluindo:

- Total de funcionários;
- Total de treinamentos;
- Treinamentos pendentes;
- Treinamentos vencidos.

### Pendências

A área de pendências permite identificar e organizar:

- Treinamentos vencidos;
- Treinamentos próximos do vencimento;
- Registros por situação;
- Filtros para facilitar a consulta.

---

## Operações do sistema

O MVP possui operações de:

- **CREATE** — cadastro de funcionários e registros de treinamentos;
- **READ** — consulta de funcionários, treinamentos, registros e indicadores;
- **UPDATE** — atualização dos dados cadastrais implementados no sistema.

---

## Tecnologias utilizadas

- **PHP**
- **PostgreSQL**
- **Supabase**
- **HTML5**
- **CSS3**
- **Git**
- **GitHub**

---

## Banco de dados

O sistema utiliza **PostgreSQL através do Supabase**.

Principais tabelas:

| Tabela | Descrição |
|---|---|
| `usuarios` | Usuários do sistema e seus níveis de acesso |
| `funcionarios` | Dados cadastrais dos trabalhadores |
| `treinamentos` | Treinamentos disponíveis |
| `registros_treinamento` | Registros dos treinamentos realizados |

### Relacionamentos

- Um funcionário pode possuir vários registros de treinamento;
- Um treinamento pode estar relacionado a vários funcionários;
- `registros_treinamento` faz a ligação entre funcionários e treinamentos.

---

## Estrutura do projeto

```text
SafeTrack/
│
├── config/
│   ├── database.php
│   ├── funcionarios.php
│   ├── registro_treinamento.php
│   ├── treinamentos.php
│   └── usuarios.php
│
├── database/
│   └── safetrack.sql
│
├── includes/
│   ├── auth.php
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   └── sidebar.php
│
├── pages/
│   ├── dashboard.php
│   ├── login.php
│   ├── meu-perfil.php
│   │
│   ├── funcionarios/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── show.php
│   │
│   ├── treinamentos/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── registrar.php
│   │
│   └── pendencias/
│       └── index.php
│
├── index.php
├── safetrack.php
├── Dockerfile
└── README.md
