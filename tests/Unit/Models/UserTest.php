<?php

declare(strict_types=1);

use App\Models\User;

test('to array includes all fillable fields', function (): void {
    $user = User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toContain(
            'id',
            'name',
            'email',
            'email_verified_at',
            'avatar_url',
            'created_by',
            'updated_by',
            'deleted_by',
            'created_at',
            'updated_at',
            'deleted_at'
        );
});
