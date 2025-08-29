<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Session::put('page','users');
        $currentRole = $request->get('role');
        $query = User::orderByDesc('created_at');
        if ($currentRole === 'vendor') {
            $query->where('role', 1);
        } elseif ($currentRole === 'customer') {
            $query->where('role', 2);
        }
        $users = $query->get();
        return view('admin.users.index', compact('users', 'currentRole'));
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = (int)!$user->is_active;
        $user->save();
        return redirect()->back()->with('success_message', 'Statut utilisateur mis à jour');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success_message', 'Utilisateur supprimé');
    }
}


