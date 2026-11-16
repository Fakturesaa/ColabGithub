<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function index(): View
    {
        return view('pendaftaran.index', [
            'items' => Pendaftaran::with(['siswa', 'ekskul'])->latest()->paginate(10),
            'totalMenunggu' => Pendaftaran::where('status', 'menunggu')->count(),
            'totalDiterima' => Pendaftaran::where('status', 'diterima')->count(),
        ]);
    }

    public function create(): View
    {
        return view('pendaftaran.form', ['item' => new Pendaftaran(), 'siswaList' => Siswa::orderBy('nama')->get(), 'ekskulList' => Ekskul::orderBy('nama_ekskul')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Pendaftaran::create($this->validated($request));
        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil dicatat.');
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        return view('pendaftaran.show', ['item' => $pendaftaran->load(['siswa', 'ekskul.pembina'])]);
    }

    public function edit(Pendaftaran $pendaftaran): View
    {
        return view('pendaftaran.form', ['item' => $pendaftaran, 'siswaList' => Siswa::orderBy('nama')->get(), 'ekskulList' => Ekskul::orderBy('nama_ekskul')->get()]);
    }

    public function update(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->update($this->validated($request));
        return redirect()->route('pendaftaran.index')->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->delete();
        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'ekskul_id' => ['required', 'exists:ekskul,id'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['required', 'in:menunggu,diterima,ditolak'],
        ]);
    }
}
