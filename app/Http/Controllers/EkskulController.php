<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Pembina;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EkskulController extends Controller
{
    public function index(): View
    {
        return view('ekskul.index', [
            'items' => Ekskul::with('pembina')->withCount([
                'pendaftaran as diterima_count' => fn ($query) => $query->where('status', 'diterima'),
            ])->latest()->paginate(10),
            'totalEkskul' => Ekskul::count(),
            'totalPendaftaran' => \App\Models\Pendaftaran::count(),
            'pembinaCount' => Pembina::count(),
        ]);
    }

    public function create(): View
    {
        return view('ekskul.form', ['item' => new Ekskul(), 'pembinaList' => Pembina::orderBy('nama')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Ekskul::create($this->validated($request));
        return redirect()->route('ekskul.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function show(Ekskul $ekskul): View
    {
        return view('ekskul.show', ['item' => $ekskul->load('pembina', 'pendaftaran.siswa')]);
    }

    public function edit(Ekskul $ekskul): View
    {
        return view('ekskul.form', ['item' => $ekskul, 'pembinaList' => Pembina::orderBy('nama')->get()]);
    }

    public function update(Request $request, Ekskul $ekskul): RedirectResponse
    {
        $ekskul->update($this->validated($request));
        return redirect()->route('ekskul.index')->with('success', 'Data ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Ekskul $ekskul): RedirectResponse
    {
        $ekskul->delete();
        return redirect()->route('ekskul.index')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'pembina_id' => ['required', 'exists:pembina,id'],
            'nama_ekskul' => ['required', 'string', 'max:255'],
            'hari_latihan' => ['required', 'string', 'max:255'],
            'kuota' => ['required', 'integer', 'min:1'],
        ]);
    }
}
