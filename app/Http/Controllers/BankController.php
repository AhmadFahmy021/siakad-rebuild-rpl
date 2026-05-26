<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banks = Bank::all();
        confirmDelete("Delete Bank!","Apakah Anda yakin ingin menghapus bank ini?");
        return view('admin.bank.index', compact('banks'));
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
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
            'nama_pemilik' => 'required',
        ]);

        Bank::create($request->all());
        Alert::success('Berhasil', 'Bank berhasil ditambahkan.');
        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        return view('admin.bank.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'nama_bank' => 'sometimes',
            'nomor_rekening' => 'sometimes',
            'nama_pemilik' => 'sometimes',
        ]);

        $bank->update($request->all());
        Alert::success('Berhasil', 'Bank berhasil diperbarui.');
        return redirect()->route('bank.index')->with('success', 'Bank berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        Alert::success('Berhasil', 'Bank berhasil dihapus.');
        return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus.');
    }
}
