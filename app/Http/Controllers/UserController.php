<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('dashboard.users.index',[
            'users' => $users
        ]);
    }


    public function create()
    {
        return view('dashboard.users.create');
    }


    public function store(Request $request)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ];
        User::create($userData);
        return redirect()->route('users.index')->with('message', 'Utilizador registado com successo.');
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'user' => $user
        ]);
    }


    public function update(Request $request, User $user)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ];
        $user->update($userData);
        return redirect()->route('users.index')->with('message', 'Utilizador atualizado com successo.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('message', 'Utilizador excluido com successo.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('dashboard.users.profile', [
            'user' => $user
        ]);
    }
}
