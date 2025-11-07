<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Support\Facades\Hash;

class TarefaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Utilizador Teste',
            'email' => 'teste@email.com',
            'password' => Hash::make('Senha-1234'),
        ]);

        Tarefa::factory()->count(15)->create([
            'user_id' => $user1->id,
            'status' => false
        ]);
        Tarefa::factory()->count(10)->create([
            'user_id' => $user1->id,
            'status' => true
        ]);

        $user2 = User::factory()->create([
            'name' => 'Outro Utilizador',
            'email' => 'outro@email.com',
            'password' => Hash::make('Senha-4321'),
        ]);

        Tarefa::factory()->count(5)->create([
            'user_id' => $user2->id,
            'status' => false
        ]);
    }
}