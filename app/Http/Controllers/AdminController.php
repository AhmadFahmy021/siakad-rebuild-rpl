<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('user')->get();
        $users = User::whereNotIn('id', $admins->pluck('user_id'))->get();
        confirmDelete("Delete Admin!","Apakah Anda yakin ingin menghapus admin ini?");
        return view('admin.kelola.admin.index', compact('admins', 'users'));
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
        $request->validate([
            'user' => 'required|exists:users,id',
            'role' => 'required',
        ]);

        $req = [
            'user_id' => $request->user,
            'role' => $request->role,
        ];

        if (Admin::where('user_id', $request->user)->exists()) {
            Alert::error('Gagal', 'Admin sudah terdaftar.');
            return redirect('/admin/kelola/account/admin');
        }

        $admin =Admin::create($req);
        Alert::success('Berhasil', 'Admin ' . strtoupper($admin->user->name) . ' berhasil ditambahkan.');
        return redirect('/admin/kelola/account/admin');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.kelola.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request  $request, Admin $admin)
    {
        $request->validate([
            'role' => 'sometimes',
        ]);

        $admin->update([
            'role' => $request->role,
        ]);
        Alert::success('Berhasil', 'Data admin ' . strtoupper($admin->user->name) . ' berhasil diperbarui.');
        return redirect('admin/kelola/account/admin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if (Auth::user()->id === $admin->user_id || $admin->id === '906c74d1-1950-4e71-bc0a-d8b6505e0b28') {
            Alert::error('Gagal', 'Anda tidak dapat menghapus admin ini.');
            return redirect('admin/kelola/account/admin');
        }

        $admin->delete();
        Alert::success('Berhasil', 'Data admin ' . strtoupper($admin->user->name) . ' berhasil dihapus.');
        return redirect('admin/kelola/account/admin');
    }
}
