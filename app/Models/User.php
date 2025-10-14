<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia, HasName, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUuids, InteractsWithMedia, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    public $incrementing = false;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'username',
        'firstname',
        'lastname',
        'email',
        'email_verified_at',
        'password',
        'avatar_url',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (User $model): void {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            $model->created_by = self::getCurrentUserId();
            $model->updated_by = self::getCurrentUserId();
        });

        self::updating(function (User $model): void {
            $model->updated_by = self::getCurrentUserId();
        });

        self::deleting(function (User $model): void {
            $model->deleted_by = self::getCurrentUserId();
            $model->save();
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $media = $this->getFirstMedia('avatars');

        return $media?->getUrl() ?? $this->avatar_url;
    }

    public function getFilamentName(): string
    {
        $firstName = $this->firstname ?? '';
        $lastName = $this->lastname ?? '';

        $fullName = mb_trim($firstName . ' ' . $lastName);

        if ($fullName !== '') {
            return $fullName;
        }

        if (! empty($this->username)) {
            return (string) $this->username;
        }

        if (! empty($this->email)) {
            return (string) $this->email;
        }

        return (string) $this->getAttribute($this->getKeyName());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile()
            ->useDisk('public');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'deleted_by');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(self::class, 'created_by');
    }

    public function updatedUsers(): HasMany
    {
        return $this->hasMany(self::class, 'updated_by');
    }

    public function deletedUsers(): HasMany
    {
        return $this->hasMany(self::class, 'deleted_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    private static function getCurrentUserId(): ?string
    {
        /** @var Guard $guard */
        $guard = app(\Illuminate\Contracts\Auth\Factory::class);

        if ($guard->check()) {
            /** @var User $user */
            $user = $guard->user();

            return $user ? $user->id : null;
        }

        return null;
    }
}
