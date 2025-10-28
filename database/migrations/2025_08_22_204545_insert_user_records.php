<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = User::query()->create([
            'username' => config('fillakit.admin_user'),
            'firstname' => config('fillakit.admin_firstname'),
            'lastname' => config('fillakit.admin_lastname'),
            'email' => config('fillakit.admin_email'),
            'email_verified_at' => now(),
            'avatar_url' => null,
            'password' => Hash::make(config('fillakit.admin_password')),
        ]);
        $user->update([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        $user->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::query()->truncate();
    }
};
