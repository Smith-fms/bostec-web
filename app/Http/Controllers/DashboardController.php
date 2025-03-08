<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Zeigt das Dashboard an.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $modules = Module::getModulesForUser($user);

        return view('dashboard.index', [
            'modules' => $modules,
            'user' => $user,
        ]);
    }
}
