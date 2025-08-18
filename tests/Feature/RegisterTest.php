<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('register', function () {
    it('should register user successfully', function () {
        $response = $this->postJson(route('register'), [
            'name' => 'John Doe',
            'email' => 'john.doe@domain.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'timestamp',
                'code',
                'status',
                'message',
            ])
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.email', 'john.doe@domain.com')
            ->assertJsonMissingPath('data.password');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@domain.com',
        ]);
        $this->assertGuest();
    });

    it('should fail to register user with invalid data', function () {
        $response = $this->postJson(route('register'), [
            'name' => null,
            'email' => null,
            'password' => null,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('password');

        $this->assertGuest();
    });
});
