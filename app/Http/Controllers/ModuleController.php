<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    /**
     * Zeigt eine Liste aller Module an.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $modules = Module::orderBy('order')->get();

        return view('modules.index', [
            'modules' => $modules,
        ]);
    }

    /**
     * Zeigt das Formular zum Erstellen eines neuen Moduls an.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();

        return view('modules.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Speichert ein neu erstelltes Modul in der Datenbank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:modules',
            'description' => 'nullable|string|max:255',
            'version' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'route' => 'required|string|max:50|unique:modules',
            'active' => 'boolean',
            'order' => 'required|integer|min:0',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write,admin',
        ]);

        $module = Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'version' => $validated['version'],
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'],
            'active' => $validated['active'] ?? false,
            'order' => $validated['order'],
        ]);

        // Rollen-Berechtigungen zuweisen
        if (!empty($validated['roles'])) {
            $rolePermissions = [];
            foreach ($validated['roles'] as $index => $roleId) {
                $permissionLevel = $validated['permissions'][$index] ?? 'read';
                $rolePermissions[$roleId] = ['permission_level' => $permissionLevel];
            }
            $module->roles()->attach($rolePermissions);
        }

        return redirect()->route('modules.index')
            ->with('success', 'Modul erfolgreich erstellt.');
    }

    /**
     * Zeigt die Details eines bestimmten Moduls an.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\View\View
     */
    public function show(Module $module)
    {
        $module->load('roles');

        return view('modules.show', [
            'module' => $module,
        ]);
    }

    /**
     * Zeigt das Formular zum Bearbeiten eines bestimmten Moduls an.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\View\View
     */
    public function edit(Module $module)
    {
        $roles = Role::all();
        $module->load('roles');

        // Bestehende Berechtigungen für das Modul abrufen
        $rolePermissions = [];
        foreach ($module->roles as $role) {
            $rolePermissions[$role->id] = $role->pivot->permission_level;
        }

        return view('modules.edit', [
            'module' => $module,
            'roles' => $roles,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Aktualisiert das angegebene Modul in der Datenbank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modules')->ignore($module->id),
            ],
            'description' => 'nullable|string|max:255',
            'version' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'route' => [
                'required',
                'string',
                'max:50',
                Rule::unique('modules')->ignore($module->id),
            ],
            'active' => 'boolean',
            'order' => 'required|integer|min:0',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write,admin',
        ]);

        $module->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'version' => $validated['version'],
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'],
            'active' => $validated['active'] ?? false,
            'order' => $validated['order'],
        ]);

        // Rollen-Berechtigungen aktualisieren
        $module->roles()->detach();
        if (!empty($validated['roles'])) {
            $rolePermissions = [];
            foreach ($validated['roles'] as $index => $roleId) {
                $permissionLevel = $validated['permissions'][$index] ?? 'read';
                $rolePermissions[$roleId] = ['permission_level' => $permissionLevel];
            }
            $module->roles()->attach($rolePermissions);
        }

        return redirect()->route('modules.index')
            ->with('success', 'Modul erfolgreich aktualisiert.');
    }

    /**
     * Entfernt das angegebene Modul aus der Datenbank.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Module $module)
    {
        $module->roles()->detach();
        $module->delete();

        return redirect()->route('modules.index')
            ->with('success', 'Modul erfolgreich gelöscht.');
    }

    /**
     * Ändert den Aktivierungsstatus eines Moduls.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive(Module $module)
    {
        $module->update([
            'active' => !$module->active,
        ]);

        $status = $module->active ? 'aktiviert' : 'deaktiviert';

        return redirect()->route('modules.index')
            ->with('success', "Modul erfolgreich {$status}.");
    }
}
