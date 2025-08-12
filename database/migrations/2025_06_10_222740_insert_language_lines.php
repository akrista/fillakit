<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\TranslationLoader\LanguageLine;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $languageLines = config('filamenter-translations');
        foreach ($languageLines as $languageLine) {
            LanguageLine::create($languageLine);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        LanguageLine::truncate();
    }
};
