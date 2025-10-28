<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\TranslationLoader\LanguageLine;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $languageLines = config('fillakit-translations');
        foreach ($languageLines as $languageLine) {
            LanguageLine::query()->create($languageLine);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        LanguageLine::query()->truncate();
    }
};
