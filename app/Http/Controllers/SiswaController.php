<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(): View
    {
        return view('siswa.index', ['items' => Siswa::latest()->paginate(10), 'total' => Siswa::count()]);
    }

    public function create(): View { return view('siswa.form', ['item' => new Siswa()]); }

    public function store(Request $request): RedirectResponse
    {
        Siswa::create($this->validated($request));
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View { return view('siswa.show', ['item' => $siswa]); }
    public function edit(Siswa $siswa): View { return view('siswa.form', ['item' => $siswa]); }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $siswa->update($this->validated($request, $siswa));
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    private function validated(Request $request, ?Siswa $siswa = null): array
    {
        return $request->validate([
            'nis' => ['required', 'string', 'max:255', 'unique:siswa,nis'.($siswa ? ','.$siswa->id : '')],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
        ]);
    }
}
