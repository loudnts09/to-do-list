<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tarefa;
use PHPUnit\Framework\Attributes\Test;

class TarefasFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function um_usuario_autenticado_pode_ver_apenas_suas_proprias_tarefas()
    {
        //cria os usuarios
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        //cria 3 tarefas para o userA
        $tarefasUserA = Tarefa::factory()->count(3)->create([
            'user_id' => $userA->id
        ]);
        
        //cria 2 tarefas para o userB
        $tarefaUserB = Tarefa::factory()->create([
            'user_id' => $userB->id
        ]);

        //simula o login como UserA
        $response = $this->actingAs($userA)->get(route('tarefas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('restrict.tarefas.index');
        
        //garante que o usuário A vê sua própria tarefa
        $response->assertSee($tarefasUserA[0]->title);
        
        //garante que o usuário A não vê a tarefa do usuário B
        $response->assertDontSee($tarefaUserB->title);
    }
    
    #[Test]
    public function a_lista_de_tarefas_deve_ser_paginada()
    {
        $user = User::factory()->create();
        
        //cria 15 tarefas
        Tarefa::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('tarefas.index'));

        $response->assertStatus(200);
        
        //verifica se a variável 'tarefas' na view é uma instância de Paginator
        $response->assertViewHas('tarefas', function ($tarefas) {
            return $tarefas instanceof \Illuminate\Pagination\LengthAwarePaginator;
        });

        //verifica se a coleção de tarefas tem 10 itens em vez de 15)
        $this->assertCount(10, $response->viewData('tarefas'));
    }

    #[Test]
    public function um_usuario_pode_filtrar_tarefas_por_status()
    {
        $user = User::factory()->create();
        
        //cria uma tarefa Pendente
        $tarefaPendente = Tarefa::factory()->create([
                'status' => false,
                'user_id' => $user->id 
            ]);

        //cia uma tarefa concluida
        $tarefaConcluida = Tarefa::factory()->create([
                'status' => true,
                'user_id' => $user->id
            ]);
        //busca apenas as pendentes
        $response = $this->actingAs($user)->get(route('tarefas.index', ['status' => 0]));

        $response->assertStatus(200);
        
        //verifica se a tarefa pendente está na lista
        $response->assertSee($tarefaPendente->title);
        
        //verifica se a tarefa concluida não está na lista
        $response->assertDontSee($tarefaConcluida->title);
    }

    #[Test]
    public function um_usuario_pode_ver_a_pagina_de_edicao_de_sua_propria_tarefa()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('tarefas.edit', $tarefa));

        $response->assertStatus(200);
        
        $response->assertViewIs('restrict.tarefas.edit');
        
        $response->assertViewHas('tarefa', $tarefa);
        
        // o título da tarefa aparece no HTML
        $response->assertSee($tarefa->title);
    }

    #[Test]
    public function um_usuario_nao_pode_ver_a_pagina_de_edicao_de_tarefa_de_outro_usuario()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        
        //tarefa pertence ao user B
        $tarefaUserB = Tarefa::factory()->create(['user_id' => $userB->id]);

        //user A tenta editar a tarefa do user B
        $response = $this->actingAs($userA)
                         ->get(route('tarefas.edit', $tarefaUserB));

        //devemos retornar não Encontrado
        $response->assertStatus(404); 
    }

    #[Test]
    public function um_usuario_pode_atualizar_sua_propria_tarefa()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $dadosAtualizados = [
            'title' => 'Título Atualizado',
            'description' => 'Descrição Atualizada',
            'status' => true,
        ];

        //simula o envio do formulário de edição
        $response = $this->actingAs($user)
                         ->put(route('tarefas.update', $tarefa), $dadosAtualizados);

        //deve redirecionar de volta para a lista
        $response->assertRedirect(route('tarefas.index'));
        
        //banco deve ter os novos dados
        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id,
            'title' => 'Título Atualizado',
            'status' => true,
        ]);
    }

    #[Test]
    public function um_usuario_nao_pode_atualizar_uma_tarefa_de_outro_usuario()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tarefaUserB = Tarefa::factory()->create(['user_id' => $userB->id, 'title' => 'Tarefa do B']);

        // user A tenta atualizar a tarefa do user B
        $response = $this->actingAs($userA)
                         ->put(route('tarefas.update', $tarefaUserB), [
                            'title' => 'Título Hackeado'
                         ]);

        // deve falhar com 404
        $response->assertStatus(404);
        
        //garantir que o banco não foi alterado
        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefaUserB->id,
            'title' => 'Tarefa do B',
        ]);
    }

    #[Test]
    public function um_usuario_pode_excluir_sua_propria_tarefa_via_soft_delete()
    {
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
                         ->delete(route('tarefas.destroy', $tarefa));

        //deve redirecionar de volta para a lista
        $response->assertRedirect(route('tarefas.index'));
        
        //verifica se a tarefa foi "marcada" como excluída (campo 'deleted_at' preenchido)
        $this->assertSoftDeleted('tarefas', [
            'id' => $tarefa->id,
        ]);
        
        //garantir que o registro AINDA EXISTE no banco
        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id,
        ]);
    }

    #[Test]
    public function um_usuario_nao_pode_excluir_uma_tarefa_de_outro_usuario()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tarefaUserB = Tarefa::factory()->create(['user_id' => $userB->id]);

        //user A tenta excluir a tarefa do User B
        $response = $this->actingAs($userA)
                         ->delete(route('tarefas.destroy', $tarefaUserB));

        //deve falhar com 404
        $response->assertStatus(404);
        
        //garantir que a tarefa NÃO foi marcada como excluída
        $this->assertNotSoftDeleted('tarefas', [
            'id' => $tarefaUserB->id,
        ]);
    }

    #[Test]
    public function um_usuario_pode_ver_sua_lixeira_de_tarefas()
    {
        $user = User::factory()->create();
        
        //tarefa ativa não deve aparecer
        $tarefaAtiva = Tarefa::factory()->create(['user_id' => $user->id]);
        
        //tareefa excluida deve aparecer
        $tarefaExcluida = Tarefa::factory()->create(['user_id' => $user->id]);
        $tarefaExcluida->delete(); 

        $response = $this->actingAs($user)
                         ->get(route('tarefas.lixeira'));

        $response->assertStatus(200);
        $response->assertViewIs('restrict.tarefas.lixeira');
        
        //vê a tarefa excluída
        $response->assertSee($tarefaExcluida->title);
        
        //não vê a tarefa ativa
        $response->assertDontSee($tarefaAtiva->title);
    }

    #[Test]
    public function um_usuario_pode_restaurar_sua_propria_tarefa()
    {
        //cria tarefa e a exclui
        $user = User::factory()->create();
        $tarefa = Tarefa::factory()->create(['user_id' => $user->id]);
        $tarefa->delete();

        //verifica se foi excluída
        $this->assertSoftDeleted($tarefa);

        //simula clique no botão de restaurar
        $response = $this->actingAs($user)
                         ->post(route('tarefas.restore', $tarefa->id));

        //redireciona para a lixeira
        $response->assertRedirect(route('tarefas.lixeira'));
        
        //verifica se a tarefa não está mais no soft delete
        $this->assertNotSoftDeleted($tarefa);
    }

    #[Test]
    public function um_usuario_nao_pode_restaurar_uma_tarefa_de_outro_usuario()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        
        //tarefa do user B é excluída
        $tarefaUserB = Tarefa::factory()->create(['user_id' => $userB->id]);
        $tarefaUserB->delete();
        $this->assertSoftDeleted($tarefaUserB);

        //user A tenta restaurar a tarefa do user B
        $response = $this->actingAs($userA)
                         ->post(route('tarefas.restore', $tarefaUserB->id));

        $response->assertStatus(404);
        
        //garantir que a tarefa continua na lixeira
        $this->assertSoftDeleted($tarefaUserB);
    }
}
