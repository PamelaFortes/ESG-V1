# 📘 INFORMATIVO — GUIA INTERNO DOS DESENVOLVEDORES

> ⚠️ ARQUIVO INTERNO DA EQUIPE
>
> Este arquivo contém informações de arquitetura, fluxo, regras e organização
> do projeto. NÃO deve ser enviado ao GitHub.
>
> Arquivo ignorado pelo `.gitignore`: `.informativo.md`

---

# 1. SOBRE O PROJETO

## Sistema de Controle de Treinamentos e Segurança do Trabalhador

Sistema desenvolvido com foco no controle de treinamentos, segurança e
indicadores relacionados ao trabalhador, tendo como referência o conceito
de ESG, principalmente o pilar Social (S).

O sistema deverá permitir que uma empresa acompanhe:

- trabalhadores cadastrados;
- setores;
- treinamentos disponíveis;
- treinamentos realizados;
- validade dos treinamentos;
- situação de conformidade dos trabalhadores;
- ocorrências de segurança;
- indicadores e relatórios;
- usuários e seus níveis de acesso.

O objetivo é transformar essas informações em um sistema centralizado,
organizado e fácil de utilizar.

---

# 2. EQUIPE E RESPONSABILIDADES

## Pamela — Líder / Dashboard / ESG / Integração

Responsabilidades:

- organização geral do projeto;
- integração das funcionalidades;
- acompanhamento das tarefas;
- Dashboard;
- indicadores ESG;
- indicadores de treinamentos;
- indicadores de conformidade;
- revisão das funcionalidades;
- organização do Git/GitHub;
- integração das branches;
- testes gerais do sistema.

Pamela não deve assumir todas as funcionalidades.

Sua função principal é garantir que as partes desenvolvidas pela equipe
funcionem juntas.

---

## Miguel — Banco de Dados

Responsabilidades:

- Supabase/PostgreSQL;
- criação das tabelas;
- relacionamentos;
- chaves primárias e estrangeiras;
- migrations;
- seed/dados de teste;
- manutenção da estrutura do banco;
- apoio aos demais integrantes com consultas SQL.

Toda alteração estrutural no banco deve ser registrada em migration.

Exemplo:

supabase/migrations/
    001_criar_setores.sql
    002_criar_trabalhadores.sql
    003_criar_treinamentos.sql
    004_criar_realizacoes.sql

Não alterar a estrutura do banco diretamente sem registrar a alteração.

---

## Wilker — Login e Autenticação

Responsabilidades:

- tela de login;
- validação de usuário;
- sessão;
- logout;
- controle de acesso;
- níveis de usuário;
- redirecionamento após login;
- proteção das páginas.

Níveis previstos:

- admin
- gestor
- trabalhador

O usuário NÃO escolhe seu nível durante o login.

O nível vem do banco de dados.

Fluxo:

LOGIN
  ↓
verifica email e senha
  ↓
busca usuário no banco
  ↓
verifica senha
  ↓
identifica nível
  ↓
cria sessão
  ↓
redireciona para área correspondente

Admin → /admin
Gestor → /gestor
Trabalhador → /trabalhador

IMPORTANTE:

O redirecionamento não é a segurança.

Cada página protegida deve verificar a sessão e o nível de acesso.

Senhas devem ser armazenadas utilizando hash.

PHP:

password_hash()
password_verify()

Nunca salvar senha em texto puro.

---

## Marcelo — Trabalhadores

Responsabilidades:

- cadastro de trabalhadores;
- listagem;
- edição;
- visualização;
- alteração de status;
- busca/filtros;
- vínculo com setor.

Dados principais:

- nome;
- CPF;
- data de nascimento;
- cargo;
- setor;
- data de admissão;
- status.

Relacionamento:

SETOR
  ↓ 1:N
TRABALHADORES

Um setor pode possuir vários trabalhadores.

---

## Carlos — Treinamentos e Realizações

Responsabilidades:

- cadastro de treinamentos;
- listagem;
- edição;
- visualização;
- registro de treinamento realizado;
- vínculo trabalhador ↔ treinamento;
- controle de validade;
- status do treinamento.

Exemplos de treinamentos:

- NR-10;
- NR-12;
- NR-35;
- Integração;
- Primeiros Socorros.

Uma realização deverá possuir informações como:

- trabalhador;
- treinamento;
- data de realização;
- data de validade;
- nota;
- status;
- observação.

---

# 3. ESTRUTURA PRINCIPAL DO SISTEMA

O sistema será dividido em módulos.

Dashboard
    ↓
Trabalhadores
    ↓
Treinamentos
    ↓
Realizações
    ↓
Ocorrências
    ↓
Relatórios
    ↓
Usuários

---

# 4. BANCO DE DADOS

Estrutura principal prevista:

## setores

- id
- nome
- descricao
- status
- data_cadastro

## trabalhadores

- id
- nome
- cpf
- data_nascimento
- cargo
- setor_id
- data_admissao
- status
- data_cadastro

## treinamentos

- id
- nome
- descricao
- carga_horaria
- validade_meses
- obrigatorio
- status
- data_cadastro

## realizacoes

- id
- trabalhador_id
- treinamento_id
- data_realizacao
- data_validade
- nota
- status
- observacao
- data_cadastro

## ocorrencias

- id
- trabalhador_id
- tipo
- gravidade
- descricao
- data_ocorrencia
- status
- medidas_tomadas
- data_cadastro

## usuarios

- id
- nome
- email
- senha
- nivel
- status
- data_cadastro

---

# 5. RELACIONAMENTOS

SETOR
  └── trabalhadores

TRABALHADOR
  ├── realizacoes
  └── ocorrencias

TREINAMENTO
  └── realizacoes

Em termos de cardinalidade:

setores 1 → N trabalhadores

trabalhadores 1 → N realizacoes

treinamentos 1 → N realizacoes

trabalhadores 1 → N ocorrencias

---

# 6. FLUXO PRINCIPAL DO SISTEMA

## Login

Usuário acessa o sistema
        ↓
Tela de login
        ↓
Email + senha
        ↓
Sistema consulta banco
        ↓
Valida senha
        ↓
Identifica nível do usuário
        ↓
Cria sessão
        ↓
Redireciona para área correta


## Admin

Admin
 ↓
Dashboard
 ↓
Gerenciar usuários
 ↓
Gerenciar trabalhadores
 ↓
Gerenciar treinamentos
 ↓
Visualizar indicadores/relatórios


## Gestor

Gestor
 ↓
Dashboard
 ↓
Visualizar trabalhadores
 ↓
Registrar treinamentos
 ↓
Acompanhar pendências
 ↓
Visualizar relatórios


## Trabalhador

Trabalhador
 ↓
Dashboard pessoal
 ↓
Meus treinamentos
 ↓
Histórico
 ↓
Validade dos treinamentos
 ↓
Perfil

---

# 7. DASHBOARD

Responsável: Pamela

O Dashboard deverá apresentar informações resumidas do sistema.

Indicadores previstos:

- total de trabalhadores;
- total de treinamentos;
- treinamentos concluídos;
- treinamentos pendentes;
- treinamentos vencidos;
- treinamentos próximos do vencimento;
- índice de conformidade;
- quantidade de ocorrências;
- situação geral de segurança/ESG.

Exemplo:

TOTAL DE TRABALHADORES
150

TREINAMENTOS CONCLUÍDOS
120

PENDENTES
20

VENCIDOS
10

CONFORMIDADE
80%

Os valores devem ser calculados a partir do banco.

Evitar números fixos/mockados no Dashboard final.

---

# 8. INDICADORES ESG

O projeto possui foco principalmente no pilar Social (S).

Os indicadores devem ajudar a demonstrar:

- capacitação dos trabalhadores;
- cumprimento de treinamentos;
- segurança;
- prevenção;
- acompanhamento de ocorrências;
- conformidade.

Exemplo de indicador:

Índice de conformidade =
treinamentos válidos / treinamentos obrigatórios × 100

Os cálculos devem ser realizados por funções.

Exemplo:

calcularIndiceConformidade()

calcularTreinamentosPendentes()

calcularTreinamentosVencidos()

---

# 9. REGRAS IMPORTANTES

## Validade de treinamento

A validade deve ser calculada utilizando:

data_realizacao + validade_meses

Exemplo:

Treinamento realizado:
01/08/2026

Validade:
12 meses

Data de validade:
01/08/2027

Situações:

VALIDO
→ ainda está dentro da validade.

PROXIMO DO VENCIMENTO
→ está próximo da data de validade.

VENCIDO
→ passou da data de validade.

---

# 10. SEGURANÇA

Nunca confiar em informações enviadas pelo navegador.

Não utilizar:

?nivel=admin

para definir permissões.

Não confiar em:

JavaScript
campos hidden
menus escondidos
redirecionamentos

A autorização deve ser validada no servidor.

Exemplo conceitual:

if (!usuarioLogado()) {
    redirecionarParaLogin();
}

if (!usuarioTemPermissao('admin')) {
    acessoNegado();
}

Senhas:

password_hash()
password_verify()

Nunca colocar senhas reais, tokens ou chaves de API no GitHub.

---

# 11. CONCEITOS PHP QUE PRECISAM APARECER

O projeto precisa demonstrar os conteúdos trabalhados nas aulas.

## Aula 01

- variáveis;
- tipos de dados;
- concatenação;
- echo;
- print;
- var_dump;
- if;
- else;
- switch;
- for;
- foreach.

## Aula 02

- arrays;
- arrays associativos;
- arrays multidimensionais;
- formulários HTML;
- $_POST;
- $_GET;
- validação;
- sanitização.

## Aula 03

- funções;
- parâmetros;
- retorno;
- modularização;
- include;
- require;
- organização do código.

Esses conceitos devem aparecer de forma útil no sistema,
não apenas para "cumprir requisito".

---

# 12.  ORGANIZAÇÃO DO PROJETO

Estrutura esperada:

sistema-esg/
│
├── admin/
├── gestor/
├── trabalhador/
├── trabalhadores/
├── treinamentos/
├── registros/
├── ocorrencias/
├── relatorios/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── auth.php
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   └── functions.php
│
├── assets/
│   ├── css/
│   └── js/
│
├── database/
│
├── index.php
├── login.php
└── README.md

A estrutura pode ser adaptada conforme o desenvolvimento,
mas não criar arquivos/pastas sem necessidade.

---

# 13.  GIT E GITHUB

## Regra principal

Ninguém deve trabalhar diretamente na `main`.

Cada funcionalidade deve possuir sua própria branch.

Exemplo:

feature/dashboard-pamela
feature/banco-miguel
feature/login-wilker
feature/trabalhadores-marcelo
feature/treinamentos-carlos

Fluxo:

main
 ↓
criar branch
 ↓
desenvolver
 ↓
commit
 ↓
push
 ↓
Pull Request
 ↓
revisão
 ↓
merge
 ↓
main

---

# 14. PADRÃO DE COMMITS

Utilizar Conventional Commits.

Formato:

tipo: descrição

Tipos permitidos:

feat
→ nova funcionalidade

fix
→ correção de bug

docs
→ documentação

style
→ alterações visuais/formatação

refactor
→ reorganização do código

test
→ testes

chore
→ manutenção/configuração

Exemplos:

feat: adiciona cadastro de trabalhadores

feat: adiciona dashboard de indicadores

fix: corrige validação do login

fix: corrige cálculo de validade

docs: atualiza README

style: ajusta responsividade do dashboard

refactor: organiza funções de autenticação

chore: atualiza gitignore

---

# 15.  O QUE NÃO FAZER

Não usar commits:

"update"

"mudanças"

"teste"

"arrumei"

"final"

"coisas"

Não fazer push direto na main.

Não alterar banco remoto sem registrar migration.

Não colocar senha ou chave de API no código.

Não criar funcionalidades paralelas sem comunicar a equipe.

Não apagar código de outro integrante sem conversar.

Não modificar arquivos de outra feature sem necessidade.

---

# 16. ORDEM DE DESENVOLVIMENTO

Para evitar dependências quebradas:

1. Banco de dados
2. Conexão com banco
3. Trabalhadores
4. Treinamentos
5. Realizações
6. Login/autenticação
7. Dashboard
8. Ocorrências
9. Relatórios
10. Testes
11. Ajustes visuais

Algumas tarefas podem ser desenvolvidas simultaneamente,
desde que suas dependências estejam definidas.

---

# 17. ⏱ORGANIZAÇÃO DAS AULAS

Cada aula possui aproximadamente 60 minutos.

Regra:

0–5 min
→ definir objetivo da aula

5–45 min
→ desenvolvimento

45–50 min
→ integração/testes

50–60 min
→ commit + push/PR + organização

Se alguém ficar mais de 5 minutos travado:

PARAR → AVISAR → PEDIR AJUDA

Não ficar 30 minutos tentando resolver sozinho enquanto
o restante da equipe avança.

---

# 18. MVP

Prioridade inicial:

[ ] Banco funcionando
[ ] Conexão com banco
[ ] Login
[ ] Níveis de acesso
[ ] Cadastro de trabalhadores
[ ] Cadastro de treinamentos
[ ] Registro de treinamentos
[ ] Dashboard
[ ] Cálculo de conformidade

Depois:

[ ] Ocorrências
[ ] Relatórios
[ ] Melhorias visuais
[ ] Filtros avançados
[ ] Melhorias de UX
[ ] Testes completos

A prioridade é TER UM SISTEMA FUNCIONANDO antes de tentar
implementar todas as funcionalidades.

---

# 19. REGRA DE OURO DA EQUIPE

Não desenvolver apenas para "terminar sua parte".

Cada integrante deve pensar:

"Minha funcionalidade precisa funcionar junto com o sistema."

Antes de considerar uma tarefa concluída:

[ ] Funciona?
[ ] Está conectada ao banco?
[ ] Está validando os dados?
[ ] Está respeitando permissões?
[ ] Não quebrou outra funcionalidade?
[ ] Foi testada?
[ ] O commit está correto?
[ ] A branch está atualizada?

---

# 20. DEFINIÇÃO DE PRONTO

Uma funcionalidade só é considerada pronta quando:

1. código desenvolvido;
2. banco integrado quando necessário;
3. validações implementadas;
4. permissões verificadas;
5. teste realizado;
6. código organizado;
7. commit realizado;
8. branch enviada;
9. Pull Request criada;
10. integração com a main validada.

---

# 21. PAPEL DA PAMELA COMO LÍDER

Responsabilidades da liderança:

- distribuir tarefas;
- acompanhar andamento;
- resolver conflitos de integração;
- revisar PRs;
- garantir padrão de código;
- organizar prioridades;
- acompanhar o prazo;
- garantir que o projeto esteja funcionando como um conjunto.

Cada integrante é responsável pela qualidade da própria feature.

Se houver problema:

DEV → avisa o responsável pela área
        ↓
responsável tenta resolver
        ↓
se necessário → equipe ajuda
        ↓
Pamela coordena a decisão final

---

# 22. OBJETIVO FINAL

Entregar um sistema funcional que demonstre:

- conhecimento de desenvolvimento web;
- PHP;
- banco de dados;
- autenticação;
- CRUD;
- relacionamentos;
- funções;
- arrays;
- formulários;
- validação;
- modularização;
- Git/GitHub;
- aplicação prática dos conceitos de ESG;
- controle de treinamentos;
- segurança do trabalhador.

O objetivo não é apenas "ter telas".

O sistema precisa possuir:

INTERFACE
    +
LÓGICA
    +
BANCO DE DADOS
    +
SEGURANÇA
    +
INDICADORES
    +
INTEGRAÇÃO

= SISTEMA FUNCIONAL