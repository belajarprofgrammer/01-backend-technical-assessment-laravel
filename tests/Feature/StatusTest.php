<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('status', function () {
    it('should update status of task successfully when authenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('status', 1), [
            'status' => 'completed',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'is_recurring',
                    'recurring_interval',
                    'created_at',
                    'updated_at',
                ],
                'timestamp',
                'code',
                'status',
                'message',
            ])
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'status' => 'completed',
        ]);
        $this->assertAuthenticated();
    });

    it('should fail to update status of task with invalid data', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('status', 1), [
            'status' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('status');

        $this->assertAuthenticated();
    });

    it('should return not found when updating non-existent status of task', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('status', 1), [
            'status' => 'completed',
        ]);

        $response->assertNotFound();

        $this->assertAuthenticated();
    });

    it('should reject status of task update when unauthenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        $response = $this->patchJson(route('status', 1), [
            'status' => 'completed',
        ]);

        $response->assertUnauthorized();

        $this->assertGuest();
    });
});
