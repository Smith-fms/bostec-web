<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'version',
        'icon',
        'route',
        'active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Die Rollen, die Zugriff auf dieses Modul haben.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'module_permissions')
            ->withPivot('permission_level')
            ->withTimestamps();
    }

    /**
     * Gibt alle aktiven Module zurück.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveModules()
    {
        return self::where('active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Gibt alle Module zurück, auf die ein Benutzer Zugriff hat.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getModulesForUser(User $user)
    {
        $roleIds = $user->roles()->pluck('roles.id');

        return self::whereHas('roles', function ($query) use ($roleIds) {
            $query->whereIn('roles.id', $roleIds);
        })
        ->where('active', true)
        ->orderBy('order')
        ->get();
    }

    /**
     * Prüft, ob ein Benutzer Zugriff auf dieses Modul hat.
     *
     * @param User $user
     * @return bool
     */
    public function isAccessibleByUser(User $user)
    {
        $roleIds = $user->roles()->pluck('roles.id');

        return $this->roles()
            ->whereIn('roles.id', $roleIds)
            ->exists();
    }

    /**
     * Gibt das höchste Berechtigungslevel eines Benutzers für dieses Modul zurück.
     *
     * @param User $user
     * @return string|null
     */
    public function getPermissionLevelForUser(User $user)
    {
        $roleIds = $user->roles()->pluck('roles.id');

        $permissions = $this->roles()
            ->whereIn('roles.id', $roleIds)
            ->pluck('permission_level')
            ->toArray();

        if (empty($permissions)) {
            return null;
        }

        // Priorität: admin > write > read
        if (in_array('admin', $permissions)) {
            return 'admin';
        } elseif (in_array('write', $permissions)) {
            return 'write';
        } else {
            return 'read';
        }
    }
}
