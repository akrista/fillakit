<?php

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
        $user = User::create([
            'name' => config('filamentry.admin_user'),
            'email' => config('filamentry.admin_email'),
            'email_verified_at' => now(),
            'avatar_url' => null,
            'password' => Hash::make(config('filamentry.admin_password')),
        ]);
        $user->update([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::truncate();
    }
};
