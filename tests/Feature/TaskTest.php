<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('task', function () {
    it('should return task list successfully', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
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
                ],
                'timestamp',
                'code',
                'status',
                'message',
            ])
            ->assertJsonCount(10, 'data');

        $this->assertDatabaseCount('tasks', 10);
        $this->assertAuthenticated();
    });

    it('should reject tasks retrieved when unauthenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        $response = $this->getJson(route('tasks.index'));

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should return task by id successfully', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson(route('tasks.show', 1));

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
            ->assertJsonPath('data.id', 1);

        $this->assertAuthenticated();
    });

    it('should return not found when retrieved by id non-existent task', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson(route('tasks.show', 1));

        $response->assertNotFound();

        $this->assertAuthenticated();
    });

    it('should reject task retrieved by id when unauthenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        $response = $this->getJson(route('tasks.show', 1));

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should create task successfully when authenticated', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson(route('tasks.store'), [
            'title' => 'Read a book',
            'description' => 'Finish reading the Laravel documentation.',
            'due_date' => '2025-12-01',
            'is_recurring' => false,
            'recurring_interval' => null,
        ]);

        $response->assertCreated()
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
            ->assertJsonPath('data.title', 'Read a book')
            ->assertJsonPath('data.description', 'Finish reading the Laravel documentation.')
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.due_date', '2025-12-01')
            ->assertJsonPath('data.is_recurring', false)
            ->assertJsonPath('data.recurring_interval', null);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Read a book',
            'description' => 'Finish reading the Laravel documentation.',
            'status' => 'pending',
            'due_date' => '2025-12-01 00:00:00',
            'is_recurring' => false,
            'recurring_interval' => null,
        ]);
        $this->assertAuthenticated();
    });

    it('should fail to create task with invalid data', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson(route('tasks.store'), [
            'title' => null,
            'description' => null,
            'due_date' => null,
            'is_recurring' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('title')
            ->assertJsonValidationErrorFor('description')
            ->assertJsonValidationErrorFor('due_date')
            ->assertJsonValidationErrorFor('is_recurring');

        $this->assertAuthenticated();
    });

    it('should reject task creation when unauthenticated', function () {
        $response = $this->postJson(route('tasks.store'), [
            'title' => 'Read a book',
            'description' => 'Finish reading the Laravel documentation.',
            'due_date' => '2025-12-01',
            'is_recurring' => false,
            'recurring_interval' => null,
        ]);

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should update task successfully when authenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create(['status' => 'pending']);

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('tasks.update', 1), [
            'title' => 'Read a book - Updated',
            'description' => 'Continue reading the Laravel documentation.',
            'due_date' => '2025-12-01',
            'is_recurring' => true,
            'recurring_interval' => 'weekly',
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
            ->assertJsonPath('data.id', 1)
            ->assertJsonPath('data.title', 'Read a book - Updated')
            ->assertJsonPath('data.description', 'Continue reading the Laravel documentation.')
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.due_date', '2025-12-01')
            ->assertJsonPath('data.is_recurring', true)
            ->assertJsonPath('data.recurring_interval', 'weekly');

        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'title' => 'Read a book - Updated',
            'description' => 'Continue reading the Laravel documentation.',
            'status' => 'pending',
            'due_date' => '2025-12-01 00:00:00',
            'is_recurring' => true,
            'recurring_interval' => 'weekly',
        ]);
        $this->assertAuthenticated();
    });

    it('should fail to update task with invalid data', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('tasks.update', 1), [
            'title' => null,
            'description' => null,
            'due_date' => null,
            'is_recurring' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('title')
            ->assertJsonValidationErrorFor('description')
            ->assertJsonValidationErrorFor('due_date')
            ->assertJsonValidationErrorFor('is_recurring');

        $this->assertAuthenticated();
    });

    it('should return not found when updating non-existent task', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('tasks.update', 1), [
            'title' => 'Read a book - Updated',
            'description' => 'Continue reading the Laravel documentation.',
            'due_date' => '2025-12-01',
            'is_recurring' => true,
            'recurring_interval' => 'weekly',
        ]);

        $response->assertNotFound();

        $this->assertAuthenticated();
    });

    it('should reject task update when unauthenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        $response = $this->patchJson(route('tasks.update', 1), [
            'title' => 'Read a book - Updated',
            'description' => 'Continue reading the Laravel documentation.',
            'due_date' => '2025-12-01',
            'is_recurring' => true,
            'recurring_interval' => 'weekly',
        ]);

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should delete task successfully when authenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(route('tasks.destroy', 1));

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => 1,
        ]);
        $this->assertAuthenticated();
    });

    it('should return not found when deleting non-existent task', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(route('tasks.destroy', 1));

        $response->assertNotFound();

        $this->assertAuthenticated();
    });

    it('should reject task deletion when unauthenticated', function () {
        $user = User::factory()->create();

        Task::factory()->count(10)
            ->for($user, 'user')
            ->create();

        $response = $this->deleteJson(route('tasks.destroy', 1));

        $response->assertUnauthorized();

        $this->assertGuest();
    });
});
