<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
 
public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['status' => true]);
    }

    public function edit($id)
    {
        return view('users.edit', ['user' => User::findOrFail($id)]);
    }

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $user->name = $request->name;
    $user->email = $request->email;

<<<<<<< HEAD
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json(['status' => true]); 
=======
    if ($request->password) {
        $user->password = bcrypt($request->password);
>>>>>>> 62fae55 (updated views)
    }

    $user->save();

    return response()->json(['status' => true]);
}

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json(['status' => true]); 
    }
}
