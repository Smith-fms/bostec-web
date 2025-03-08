<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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
    ];

    /**
     * Die Benutzer, die diese Rolle haben.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Die Module, auf die diese Rolle Zugriff hat.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_permissions')
            ->withPivot('permission_level')
            ->withTimestamps();
    }

    /**
     * Prüft, ob die Rolle Zugriff auf ein bestimmtes Modul hat.
     *
     * @param string|Module $module
     * @return bool
     */
    public function hasAccessToModule($module)
    {
        if (is_string($module)) {
            $module = Module::where('name', $module)->first();
            if (!$module) {
                return false;
            }
        }

        return $this->modules()->where('modules.id', $module->id)->exists();
    }

    /**
     * Prüft, ob die Rolle ein bestimmtes Berechtigungslevel für ein Modul hat.
     *
     * @param string|Module $module
     * @param string $level
     * @return bool
     */
    public function hasPermissionLevel($module, $level)
    {
        if (is_string($module)) {
            $module = Module::where('name', $module)->first();
            if (!$module) {
                return false;
            }
        }

        $permission = $this->modules()
            ->where('modules.id', $module->id)
            ->first();

        return $permission && $permission->pivot->permission_level === $level;
    }

    /**
     * Gewährt der Rolle Zugriff auf ein Modul mit einem bestimmten Berechtigungslevel.
     *
     * @param string|Module $module
     * @param string $level
     * @return void
     */
    public function grantAccessToModule($module, $level = 'read')
    {
        if (is_string($module)) {
            $module = Module::where('name', $module)->firstOrFail();
        }

        $this->modules()->syncWithoutDetaching([
            $module->id => ['permission_level' => $level]
        ]);
    }

    /**
     * Entzieht der Rolle den Zugriff auf ein Modul.
     *
     * @param string|Module $module
     * @return void
     */
    public function revokeAccessToModule($module)
    {
        if (is_string($module)) {
            $module = Module::where('name', $module)->firstOrFail();
        }

        $this->modules()->detach($module);
    }
}
