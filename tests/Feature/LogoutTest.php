<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('logout', function () {
    it('should logout successfully', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(route('logout'));

        $response->assertNoContent();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
        $this->assertAuthenticated();
    });
});
