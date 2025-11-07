# Gestor de Tarefas (To-Do List)

Esta é uma aplicação Laravel completa desenvolvida com o objetivo de gerir uma lista de tarefas (to-do list), com autenticação, CRUD completo e lógica de soft-delete.

O projeto foi desenvolvido seguindo a metodologia TDD (Test-Driven Development) para garantir a cobertura de todos os requisitos funcionais e de segurança.

---

## Tecnologias Utilizadas

* **Framework:** Laravel 12
* **Linguagem:** PHP 8.2+
* **Banco de Dados:** MySQL
* **Frontend:** Blade com Bootstrap 5
* **Testes:** PHPUnit (Testes de Funcionalidade)
* **Autenticação:** Sistema de autenticação manual (Controllers + Services/Repositories)

---

## Funcionalidades Implementadas

O projeto cobre todos os requisitos solicitados:

* **Autenticação:** Sistema completo de Login (com validação) e Logout.
* **Segurança:** As rotas de tarefas são protegidas por *middleware*. Um utilizador só pode ver, editar ou excluir as suas próprias tarefas.
* **CRUD de Tarefas:**
    * **Criação:** Criar novas tarefas (com `user_id` associado automaticamente).
    * **Listagem (Read):** Listar todas as tarefas do utilizador, com paginação.
    * **Edição (Update):** Atualizar título, descrição e status.
    * **Exclusão (Delete):** Mover tarefas para a lixeira.
* **Filtro:** Filtrar a lista de tarefas por status (Pendente ou Concluída).
* **Soft Deletes:**
    * As tarefas excluídas vão para uma **Lixeira** (página separada).
    * As tarefas na lixeira podem ser **Restauradas**.
* **Validação:** Uso de `FormRequest` para validar todos os dados de entrada.
* **Views:** Uso de *layouts* Blade (`app.blade.php`) e *partials* (`_alerts`, `_delete_modal`) para evitar repetição de código.
* **Testes (TDD):** Cobertura de testes de funcionalidade para todos os métodos do CRUD.

---

## 🔧 Instruções de Instalação Local

Siga estes passos para rodar o projeto localmente.

**1. Clonar o Repositório**
```bash
git clone https://github.com/loudnts09/to-do-list.git
cd to-do-list
```

**2. Instalar Dependências**

Instale as dependências do Composer e do NPM.
```bash
composer install
npm install
```

**3. Configurar o Ambiente**

Copie o ficheiro de ambiente de exemplo e gere a chave da aplicação.

```bash
cp .env.example .env
php artisan key:generate
```

**4. Configurar o Banco de Dados (MySQL)**

Crie um novo banco de dados no seu gestor MySQL (ex: todo_list). Depois, edite o arquivo .env com as credenciais:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list
DB_USERNAME=root
DB_PASSWORD=root

```

**5. Rodar as Migrations e Seeders**

Este comando irá apagar o banco (se ele existir), criar todas as tabelas e popular o banco com um utilizador de teste.
```bash
php artisan migrate:fresh --seed
```
**5. Iniciar o Servidor**

```bash
php artisan serve
```
A aplicação estará disponível em http://127.0.0.1:8000.

---
## Acesso ao Sistema

Após rodar os seeders (--seed), pode aceder ao sistema com o utilizador de teste padrão.

Email: teste@email.com 

Senha: Senha-1234

---
## Rodar os testes

Para executar a suíte de testes TDD (que cobre todas as funcionalidades), certifique-se de que a sua extensão pdo_sqlite do PHP está ativa.

O Laravel está configurado para usar um banco de dados SQLite em memória para testes (via phpunit.xml).

```bash
php artisan test
```
