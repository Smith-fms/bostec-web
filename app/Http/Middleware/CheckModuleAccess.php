<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleName, string $permissionLevel = 'read'): Response
    {
        $user = $request->user();
        
        if (!$user) {
            abort(403, 'Zugriff verweigert. Sie müssen angemeldet sein.');
        }

        $module = Module::where('name', $moduleName)->first();
        
        if (!$module) {
            abort(404, 'Modul nicht gefunden.');
        }

        if (!$module->active) {
            abort(403, 'Dieses Modul ist derzeit deaktiviert.');
        }

        $userPermissionLevel = $module->getPermissionLevelForUser($user);
        
        if (!$userPermissionLevel) {
            abort(403, 'Zugriff verweigert. Sie haben keinen Zugriff auf dieses Modul.');
        }

        // Prüfen, ob der Benutzer die erforderliche Berechtigungsstufe hat
        $hasAccess = false;
        
        switch ($permissionLevel) {
            case 'read':
                // Jede Berechtigungsstufe erlaubt Lesezugriff
                $hasAccess = in_array($userPermissionLevel, ['read', 'write', 'admin']);
                break;
            case 'write':
                // Nur write und admin erlauben Schreibzugriff
                $hasAccess = in_array($userPermissionLevel, ['write', 'admin']);
                break;
            case 'admin':
                // Nur admin erlaubt Administratorzugriff
                $hasAccess = $userPermissionLevel === 'admin';
                break;
        }

        if (!$hasAccess) {
            abort(403, 'Zugriff verweigert. Sie haben nicht die erforderlichen Berechtigungen für diese Aktion.');
        }

        return $next($request);
    }
}
