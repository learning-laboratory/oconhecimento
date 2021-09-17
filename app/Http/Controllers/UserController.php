<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\CreateUserRequest;
use App\Models\Article;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('dashboard.users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('display_name','id');
        return view('dashboard.users.create',[
            'roles' => $roles
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $userData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'description'  => $request->description,
            'is_suspended' => $request->is_suspended,
            'photo_id' => $this->uploadPhotoAndReturnPhotoId($request),
        ];

        $user = User::create($userData);
        $role = Role::findOrFail($request->role_id);
        $user->roles()->sync([$role->id]);

        return redirect()->route('users.index')->with('message', 'Utilizador registado com successo.');
    }

    public function uploadPhotoAndReturnPhotoId(Request $request, User $user = null) :null|int
    {

        if(!$request->photo  && $user && $user->photo)
            return $user->photo->id;

        if(!$request->photo)
            return null;

        $path = $request->file('photo')->store('users', 'public');

        if($user && $user->photo){
            $user->photo->update(['path' => $path]);
            return $user->photo->id;
        }

        $photo = Photo::create(['path' => $path]);
        return $photo->id;
    }


    public function edit(User $user)
    {
        $roles = Role::pluck('display_name', 'id');
        return view('dashboard.users.edit', [
            'user'  => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $userData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'description'  => $request->description,
            'is_suspended' => $request->is_suspended,
            'photo_id' => $this->uploadPhotoAndReturnPhotoId($request, $user),
        ];

        $user->update($userData);
        $role = Role::findOrFail($request->role_id);
        $user->roles()->sync([$role->id]);

        return redirect()->route('users.index')->with('message', 'Utilizador atualizado com successo.');
    }

    public function destroy(User $user)
    {
        if ($user->photo) {
            $path = 'storage/'.$user->photo->path;
            if (file_exists($path))
                unlink($path);
            $user->photo()->delete();
        }

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
