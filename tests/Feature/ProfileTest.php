<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('profile', function () {
    it('should return profile by user successfully', function () {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@domain.com',
            'password' => Hash::make('SecurePass123!'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson(route('profile.show'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'timestamp',
                'code',
                'status',
                'message',
            ])
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.email', 'john.doe@domain.com');

        $this->assertAuthenticated();
    });

    it('should reject profile retrieved by user when unauthenticated', function () {
        $response = $this->getJson(route('profile.show'));

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should update profile successfully when authenticated', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('profile.update'), [
            'name' => 'John Smith',
            'email' => 'john.doe@domain.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'timestamp',
                'code',
                'status',
                'code',
            ])
            ->assertJsonPath('data.name', 'John Smith')
            ->assertJsonPath('data.email', 'john.doe@domain.com');

        $this->assertDatabaseHas('users', [
            'name' => 'John Smith',
            'email' => 'john.doe@domain.com',
        ]);
        $this->assertAuthenticated();
    });

    it('should fail to update profile with invalid data', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson(route('profile.update'), [
            'name' => null,
            'email' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email');

        $this->assertAuthenticated();
    });

    it('should reject profile update when unauthenticated', function () {
        $response = $this->patchJson(route('profile.update'), [
            'name' => 'John Smith',
            'email' => 'john.doe@domain.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertUnauthorized();

        $this->assertGuest();
    });

    it('should delete profile successfully when authenticated', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(route('profile.destroy'));

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        $this->assertAuthenticated();
    });

    it('should reject profile deletion when unauthenticated', function () {
        $response = $this->deleteJson(route('profile.destroy'));

        $response->assertUnauthorized();

        $this->assertGuest();
    });
});
