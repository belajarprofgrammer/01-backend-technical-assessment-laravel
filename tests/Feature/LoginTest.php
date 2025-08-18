<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('login', function () {
    it('should login successfully', function () {
        $user = User::factory()->create([
            'email' => 'john.doe@domain.com',
            'password' => Hash::make('SecurePass123!'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'john.doe@domain.com',
            'password' => 'SecurePass123!',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'expired_at',
                ],
                'timestamp',
                'code',
                'status',
                'message',
            ])
            ->assertJsonPath('data.access_token', fn (string $token) => strlen($token) >= 50);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
        $this->assertAuthenticated();
    });

    it('should fail to login with invalid data', function () {
        $response = $this->postJson(route('login'), [
            'email' => null,
            'password' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('password');

        $this->assertGuest();
    });
});
