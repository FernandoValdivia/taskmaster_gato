<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario para las pruebas
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum'); // Autenticar al usuario con Sanctum
    }

    /** @test */
    public function it_can_list_tasks()
    {
        // Crear tareas asociadas al usuario
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        // Hacer una solicitud GET al endpoint de tareas
        $response = $this->getJson('/api/tasks');

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data'); // Verificar que se devuelvan 3 tareas
    }

    /** @test */
    public function it_can_show_a_task()
    {
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Hacer una solicitud GET para obtener la tarea
        $response = $this->getJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200)
                ->assertJson(['data' => ['id' => $task->id]]);
    }

    /** @test */
    public function it_cannot_show_a_task_of_another_user()
    {
        // Crear un segundo usuario
        $anotherUser = User::factory()->create();

        // Crear una tarea asociada al segundo usuario
        $task = Task::factory()->create(['user_id' => $anotherUser->id]);

        // Hacer una solicitud GET para obtener la tarea
        $response = $this->getJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta sea un error 403 (Prohibido)
        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Datos actualizados
        $updatedData = [
            'title' => 'Tarea actualizada',
            'status' => 'completed',
        ];

        // Hacer una solicitud PUT para actualizar la tarea
        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200)
                ->assertJson(['data' => $updatedData]);

        // Verificar que la tarea se haya actualizado en la base de datos
        $this->assertDatabaseHas('tasks', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        // Crear una tarea asociada al usuario
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Hacer una solicitud DELETE para eliminar la tarea
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(204);

        // Verificar que la tarea se haya eliminado de la base de datos
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function it_cannot_delete_a_task_of_another_user()
    {
        // Crear un segundo usuario
        $anotherUser = User::factory()->create();

        // Crear una tarea asociada al segundo usuario
        $task = Task::factory()->create(['user_id' => $anotherUser->id]);

        // Hacer una solicitud DELETE para eliminar la tarea
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Verificar que la respuesta sea un error 403 (Prohibido)
        $response->assertStatus(403);
    }

    public function it_cannot_update_a_task_of_another_user()
    {
        // Crear un segundo usuario
        $anotherUser = User::factory()->create();

        // Crear una tarea asociada al segundo usuario
        $task = Task::factory()->create(['user_id' => $anotherUser->id]);

        // Datos actualizados
        $updatedData = [
            'title' => 'Tarea actualizada',
            'status' => 'completed',
        ];

        // Hacer una solicitud PUT para actualizar la tarea
        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        // Verificar que la respuesta sea un error 403 (Prohibido)
        $response->assertStatus(403);
    }
}