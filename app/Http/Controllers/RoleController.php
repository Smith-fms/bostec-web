<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Zeigt eine Liste aller Rollen an.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();

        return view('roles.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Zeigt das Formular zum Erstellen einer neuen Rolle an.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $modules = Module::all();

        return view('roles.create', [
            'modules' => $modules,
        ]);
    }

    /**
     * Speichert eine neu erstellte Rolle in der Datenbank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write,admin',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Module-Berechtigungen zuweisen
        if (!empty($validated['modules'])) {
            $modulePermissions = [];
            foreach ($validated['modules'] as $index => $moduleId) {
                $permissionLevel = $validated['permissions'][$index] ?? 'read';
                $modulePermissions[$moduleId] = ['permission_level' => $permissionLevel];
            }
            $role->modules()->attach($modulePermissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rolle erfolgreich erstellt.');
    }

    /**
     * Zeigt die Details einer bestimmten Rolle an.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function show(Role $role)
    {
        $role->load(['users', 'modules']);

        return view('roles.show', [
            'role' => $role,
        ]);
    }

    /**
     * Zeigt das Formular zum Bearbeiten einer bestimmten Rolle an.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $modules = Module::all();
        $role->load('modules');

        // Bestehende Berechtigungen für die Rolle abrufen
        $modulePermissions = [];
        foreach ($role->modules as $module) {
            $modulePermissions[$module->id] = $module->pivot->permission_level;
        }

        return view('roles.edit', [
            'role' => $role,
            'modules' => $modules,
            'modulePermissions' => $modulePermissions,
        ]);
    }

    /**
     * Aktualisiert die angegebene Rolle in der Datenbank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => 'nullable|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write,admin',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Module-Berechtigungen aktualisieren
        $role->modules()->detach();
        if (!empty($validated['modules'])) {
            $modulePermissions = [];
            foreach ($validated['modules'] as $index => $moduleId) {
                $permissionLevel = $validated['permissions'][$index] ?? 'read';
                $modulePermissions[$moduleId] = ['permission_level' => $permissionLevel];
            }
            $role->modules()->attach($modulePermissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rolle erfolgreich aktualisiert.');
    }

    /**
     * Entfernt die angegebene Rolle aus der Datenbank.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        // Prüfen, ob die Rolle noch Benutzern zugeordnet ist
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Die Rolle kann nicht gelöscht werden, da sie noch Benutzern zugeordnet ist.');
        }

        $role->modules()->detach();
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rolle erfolgreich gelöscht.');
    }
}
