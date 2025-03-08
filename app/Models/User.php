<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'vorname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Die Rollen, die dem Benutzer zugeordnet sind.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Prüft, ob der Benutzer eine bestimmte Rolle hat.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Prüft, ob der Benutzer eine von mehreren Rollen hat.
     *
     * @param array $roleNames
     * @return bool
     */
    public function hasAnyRole($roleNames)
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    /**
     * Prüft, ob der Benutzer alle angegebenen Rollen hat.
     *
     * @param array $roleNames
     * @return bool
     */
    public function hasAllRoles($roleNames)
    {
        return $this->roles()->whereIn('name', $roleNames)->count() === count($roleNames);
    }

    /**
     * Weist dem Benutzer eine Rolle zu.
     *
     * @param string|Role $role
     * @return void
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        
        $this->roles()->syncWithoutDetaching($role);
    }

    /**
     * Entfernt eine Rolle vom Benutzer.
     *
     * @param string|Role $role
     * @return void
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        
        $this->roles()->detach($role);
    }

    /**
     * Gibt den vollständigen Namen des Benutzers zurück.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->vorname} {$this->name}";
    }
}
