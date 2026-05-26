<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function hasPermission(string $routeName): bool
    {
        // Check if explicitly excluded first
        if ($this->permissions()
            ->where('route_name', $routeName)
            ->wherePivot('type', 'exclude')
            ->exists()) {
            return false; // blocked regardless of role
        }

        // Check if directly included
        if ($this->permissions()
            ->where('route_name', $routeName)
            ->wherePivot('type', 'include')
            ->exists()) {
            return true;
        }

        // Fall back to role-based permissions
        return $this->roles()
            ->whereHas('permissions', fn($q) => $q->where('route_name', $routeName))
            ->exists();
    }
}
