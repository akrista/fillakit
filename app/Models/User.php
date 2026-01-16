<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\Guard;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasAvatar, HasEmailAuthentication, HasMedia, HasName
    // , MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasUuids;
    use InteractsWithMedia;
    use Notifiable;
    use SoftDeletes;

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
        'filament_authentication_secret',
        'filament_authentication_recovery_codes',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        // This method should return the user's saved app authentication secret.
        return $this->filament_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        // This method should save the user's app authentication secret.
        $this->filament_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        // In a user's authentication app, each account can be represented by a "holder name".
        // If the user has multiple accounts in your app, it might be a good idea to use
        // their email address as then they are still uniquely identifiable.

        return $this->email;
    }

    /**
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        // This method should return the user's saved app authentication recovery codes.
        return $this->filament_authentication_recovery_codes;
    }

    /**
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        // This method should save the user's app authentication recovery codes.
        $this->filament_authentication_recovery_codes = $codes;
        $this->save();
    }

    public function hasEmailAuthentication(): bool
    {
        // This method should return true if the user has enabled email authentication.
        return $this->has_email_authentication;
    }

    public function toggleEmailAuthentication(bool $condition): void
    {
        // This method should save whether or not the user has enabled email authentication.
        $this->has_email_authentication = $condition;
        $this->save();
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

    protected static function boot(): void
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
            'filament_authentication_secret' => 'encrypted',
            'filament_authentication_recovery_codes' => 'encrypted:array',
            'has_email_authentication' => 'boolean',
        ];
    }

    private static function getCurrentUserId(): ?string
    {
        /** @var Guard $guard */
        $guard = resolve(\Illuminate\Contracts\Auth\Factory::class);

        if ($guard->check()) {
            /** @var User $user */
            $user = $guard->user();

            return $user ? $user->id : null;
        }

        return null;
    }
}
