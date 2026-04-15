<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Jabatan;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $data = Karyawan::with('jabatan')
            ->when($search, function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })->paginate(10);
            
        $jabatans = Jabatan::all(); // Untuk dropdown di modal
        return view('karyawan_index', compact('data', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required', 'jabatan_id' => 'required']);
        Karyawan::create($request->all());
        return redirect('/')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required', 'jabatan_id' => 'required']);
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());
        return redirect('/')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        Karyawan::destroy($id);
        return redirect('/')->with('success', 'Data berhasil dihapus!');
    }
}