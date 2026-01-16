<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\TranslationLoader\LanguageLine as SpatieLanguageLine;

final class LanguageLine extends SpatieLanguageLine
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
}
