# Descrição das Decisões de Arquitetura

Este documento descreve as principais decisões técnicas e de arquitetura tomadas durante o desenvolvimento da aplicação **To-Do List**.

---

## 1. Arquitetura e Padrões de Design


### Autenticação (Service/Repository Pattern)
Para as funcionalidades de **Autenticação (Login)** e **Registo de Utilizador**, foi implementado o **Padrão de Serviço** (`UserService`, `UserAuthService`) e **Repositório** (`UserRepository`).

Essa abstração foi escolhida porque a lógica de utilizador — como verificação de e-mails duplicados e hashing de senhas — é uma responsabilidade de negócio, que beneficia de estar separada dos controllers.

---

### CRUD de Tarefas (Lógica no Controller)
Para o CRUD de Tarefas, a lógica de negócio (filtros, autorização) foi mantida dentro do `TarefaController`.  
Essa decisão foi tomada pelos seguintes motivos:

#### Validação via FormRequest
Os requisitos de validação (`StoreTarefaRequest`, `UpdateTarefaRequest`) já removem a lógica de validação do controller.

#### Autorização via FormRequest
A lógica de segurança — *"o utilizador pode atualizar esta tarefa?"* — foi centralizada no método `authorize()` do `UpdateTarefaRequest`, limpando ainda mais o controller.

#### Simplicidade do TDD
Manter a lógica de consulta (`where('user_id', ...)`) e segurança (`abort(404)`) **no controller** tornou o ciclo de TDD (*Red-Green-Refactor*) mais rápido e direto para os testes de funcionalidade.

---

## 2. Metodologia de Teste (TDD)

A estratégia de TDD focou-se principalmente em Testes de Caixa-Preta (Black-Box), que no Laravel são chamados de Testes de Funcionalidade (Feature Tests).

---

### O que foi feito

Foi utilizada a suíte de Testes de Caixa-Preta do Laravel em `tests/Feature/TarefasFeatureTest.php`.

A suíte de testes simula um utilizador navegando na aplicação.  
Ela prova que, dado um input (ex: `GET /tarefas/lixeira`), o sistema devolve o output correto (`assertSee('Tarefa Excluída')`).

---

### Cobertura dos Testes

A suíte de testes desenvolvida cobre **100% dos requisitos funcionais**, incluindo:

- Testes de listagem, paginação e filtro.  
- Testes de criação, edição e validação.  
- Testes de Soft Delete e Restauração.  
- Testes de Segurança (Bónus), garantindo que um utilizador não pode aceder a dados de outro.

---

## Conclusão e Próximos Passos

A arquitetura da aplicação To-Do List foi projetada com foco em:

- Separação de responsabilidades, garantindo que cada camada (Controller, Service, Repository) cumpra um papel específico.  
- Facilidade de manutenção e extensão, permitindo evoluções futuras com baixo acoplamento.  
- Testabilidade, graças à aplicação sistemática da metodologia TDD.  
- Segurança e isolamento de dados, assegurando que cada utilizador só interaja com os seus próprios recursos.  

### Próximos Passos

1. Implementar testes unitários adicionais para cobrir casos específicos no `UserService` e `UserRepository`.  
2. Adicionar testes de integração para verificar a comunicação entre módulos.  
3. Refatorar o front-end para introduzir componentes reutilizáveis.  
4. Incluir cache e otimização de queries, visando melhorar o desempenho da aplicação.  
5. Implementar API REST com autenticação JWT, ampliando o acesso a clientes externos.  

---

**Resumo Final:**  
A aplicação foi construída sobre uma base sólida de boas práticas de engenharia de software, priorizando qualidade, clareza e confiabilidade.  
As decisões aqui documentadas refletem um equilíbrio entre simplicidade arquitetural e robustez técnica, permitindo que o sistema evolua de forma sustentável.