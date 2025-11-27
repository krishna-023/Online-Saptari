<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',              // admin, super-admin, user
        'profile_picture',
        'permissions',       // optional, store custom JSON permissions
        'theme', // Add this
        'notification_settings', // Add this
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array', // JSON to array
        'notification_settings' => 'array', // Add this cast
    ];

    /**
     * Check if user has a permission.
     */
   public function hasPermission(string $permission): bool
{
    // Super admin has all permissions
    if ($this->isSuperAdmin()) {
        return true;
    }

    // Check if user has specific permission
    $userPermissions = $this->permissions ?? [];

    return in_array($permission, $userPermissions) ||
           in_array('all', $userPermissions);
}

public function hasAnyPermission(array $permissions): bool
{
    foreach ($permissions as $permission) {
        if ($this->hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

public function isSuperAdmin(): bool
{
    return $this->role === 'super-admin';
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isUser(): bool
{
    return $this->role === 'user';
}

// Get default permissions for a role
public function getDefaultPermissions(): array
{
    return config("role_permissions.default_permissions." . $this->role, []);
}

    /**
     * Relationship example: banners
     */
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
