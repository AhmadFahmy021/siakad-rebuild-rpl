<?php

namespace App\Http\Controllers;

// use App\Models\user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        confirmDelete("Delete User!","Apakah Anda yakin ingin menghapus user ini?");
        return view('admin.kelola.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
        ]);

        $request->merge(['password' => Hash::make($request->password)]);

        User::create($request->all());
        Alert::success('Berhasil', 'User berhasil ditambahkan.');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.kelola.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'username' => 'sometimes|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8',
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        Alert::success('Berhasil', 'User berhasil diperbarui.');
        return redirect("/admin/kelola/user");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        Alert::success('Berhasil', 'User berhasil dihapus.');
        return redirect('/admin/kelola/user');
    }
}
