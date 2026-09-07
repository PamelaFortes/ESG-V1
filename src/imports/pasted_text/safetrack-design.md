Crie o design completo das telas de um sistema web chamado SafeTrack, voltado para controle de treinamentos e segurança de trabalhadores de uma empresa de manutenção predial e facilities.

O sistema será utilizado pelo setor administrativo/RH e pelo responsável pela segurança do trabalho para acompanhar funcionários, treinamentos obrigatórios, validade dos treinamentos e pendências.

IMPORTANTE:

Criar SOMENTE as telas e o design da interface.
Não criar código.
Não criar banco de dados.
Não criar protótipo funcional.
Não adicionar funcionalidades que não foram solicitadas.
O projeto deve ter aparência de um sistema corporativo real, simples e profissional.
Priorizar clareza, facilidade de uso e visualização rápida de pendências.
Design desktop-first para uma aplicação web.
Criar componentes reutilizáveis e manter o mesmo padrão visual em todas as telas.
Identidade visual

Utilize um visual moderno, profissional e relacionado à segurança do trabalho.

Cores principais:

Azul escuro para navegação e elementos principais
Azul médio como cor de destaque
Branco e cinza claro para fundos
Amarelo/laranja para alertas e treinamentos próximos do vencimento
Vermelho para treinamentos vencidos e situações críticas
Verde para treinamentos válidos e situações concluídas

Evite aparência excessivamente tecnológica ou futurista. O sistema deve parecer uma ferramenta corporativa utilizada diariamente por uma empresa.

Utilize:

Tipografia moderna e legível
Cards com cantos levemente arredondados
Ícones simples
Tabelas organizadas
Espaçamento confortável
Hierarquia visual clara
Estados de sucesso, alerta, erro e informação
TELAS DO MVP

Crie as seguintes telas:

1. Tela de Login

Tela simples e profissional.

Elementos:

Logo/nome SafeTrack
Título "Controle de Treinamentos e Segurança"
Campo de e-mail
Campo de senha
Checkbox "Lembrar-me"
Botão "Entrar"
Link "Esqueci minha senha"

Não adicionar cadastro público de usuários.

2. Dashboard / Painel de Pendências

Esta é a principal tela do sistema após o login.

Criar um menu lateral com:

Dashboard
Funcionários
Treinamentos
Pendências
Relatórios

No topo:

Nome do sistema
Campo de busca
Avatar/nome do usuário logado

No conteúdo principal:

Título:
"Visão geral"

Criar cards de indicadores:

Funcionários cadastrados
Treinamentos válidos
Treinamentos próximos do vencimento
Treinamentos vencidos

Criar uma seção de destaque chamada:
"Pendências de treinamento"

Mostrar uma tabela com:

Funcionário
Cargo
Treinamento
Data de validade
Situação
Ação

Usar badges coloridas:

Válido
Próximo do vencimento
Vencido

Adicionar uma pequena seção "Próximos vencimentos" mostrando treinamentos que irão vencer nos próximos dias.

O dashboard deve permitir que o usuário identifique rapidamente quais funcionários precisam de atenção.

3. Lista de Funcionários

Criar uma tela para gerenciamento dos funcionários.

Título:
"Funcionários"

Adicionar:

Botão "+ Novo funcionário"
Campo de pesquisa
Filtro por cargo
Filtro por situação

Tabela contendo:

Nome
CPF
Cargo
Setor
Quantidade de treinamentos
Situação
Ações

Cada funcionário deve possuir uma ação para visualizar seus detalhes.

4. Cadastro de Funcionário

Criar formulário para cadastro de funcionário.

Campos:

Dados pessoais:

Nome completo
CPF
Data de nascimento
E-mail
Telefone

Dados profissionais:

Cargo
Setor
Data de admissão
Matrícula
Status do funcionário

Botões:
"Cancelar"
"Salvar funcionário"

Organizar o formulário em seções e utilizar uma estrutura limpa e fácil de preencher.

5. Perfil / Detalhes do Funcionário

Criar uma tela mostrando os dados completos de um funcionário.

No topo:

Nome do funcionário
Cargo
Setor
Status

Criar resumo com:

Total de treinamentos
Treinamentos válidos
Treinamentos próximos do vencimento
Treinamentos vencidos

Abaixo, criar uma tabela:
"Histórico de treinamentos"

Colunas:

Treinamento
Data de realização
Data de validade
Situação
Ações

Adicionar botão:
"+ Registrar treinamento"

6. Lista de Treinamentos

Criar tela para gerenciamento dos tipos de treinamento.

Título:
"Treinamentos"

Botão:
"+ Novo treinamento"

Tabela contendo:

Nome do treinamento
Descrição
Periodicidade/validade
Quantidade de funcionários
Status
Ações

Exemplos de treinamentos:

Integração de Segurança
Trabalho em Altura
Segurança com Ferramentas
Primeiros Socorros
Uso de Equipamentos de Proteção
7. Cadastro de Treinamento

Criar formulário para cadastrar um tipo de treinamento.

Campos:

Nome do treinamento
Descrição
Carga horária
Validade do treinamento
Status

Botões:
"Cancelar"
"Salvar treinamento"

8. Registro de Treinamento Realizado

Criar formulário para registrar que um funcionário realizou determinado treinamento.

Campos:

Funcionário
Treinamento
Data de realização
Data de validade
Observações

Adicionar área visual para anexar certificado, mas apenas como elemento visual da interface.

Botões:
"Cancelar"
"Registrar treinamento"

9. Tela de Pendências

Criar uma tela dedicada exclusivamente às pendências.

Título:
"Pendências"

Criar filtros:

Todos
Vencidos
Próximos do vencimento
Funcionário
Treinamento
Setor

Criar tabela com:

Funcionário
Treinamento
Setor
Data de realização
Data de validade
Dias restantes
Situação
Ação

Dar bastante destaque visual aos treinamentos vencidos.

Criar uma pequena área de resumo no topo:

Vencidos
Vencem em 7 dias
Vencem em 30 dias
Estrutura geral

Todas as telas internas devem compartilhar:

Sidebar fixa
Header superior
Breadcrumb quando necessário
Mesmo sistema de cores
Mesmo padrão de botões
Mesmos estilos de tabela
Mesmos badges de status
Cards padronizados
Formulários consistentes

Crie também os estados visuais de:

Campo obrigatório
Erro de validação
Sucesso
Lista vazia
Confirmação de exclusão

O resultado deve parecer um MVP real de um sistema corporativo de gestão de segurança e treinamentos, com foco em funcionários, treinamentos e controle de vencimentos.

Não criar telas para funcionalidades que não fazem parte deste MVP, como gestão de acidentes/incidentes ou relatórios avançados. Essas funcionalidades podem ficar fora desta primeira versão.