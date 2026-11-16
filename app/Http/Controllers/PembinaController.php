<?php

namespace App\Http\Controllers;

use App\Models\Pembina;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembinaController extends Controller
{
    public function index(): View
    {
        return view('pembina.index', ['items' => Pembina::latest()->paginate(10), 'total' => Pembina::count()]);
    }

    public function create(): View { return view('pembina.form', ['item' => new Pembina()]); }

    public function store(Request $request): RedirectResponse
    {
        Pembina::create($this->validated($request));
        return redirect()->route('pembina.index')->with('success', 'Data pembina berhasil ditambahkan.');
    }

    public function show(Pembina $pembina): View { return view('pembina.show', ['item' => $pembina]); }
    public function edit(Pembina $pembina): View { return view('pembina.form', ['item' => $pembina]); }

    public function update(Request $request, Pembina $pembina): RedirectResponse
    {
        $pembina->update($this->validated($request));
        return redirect()->route('pembina.index')->with('success', 'Data pembina berhasil diperbarui.');
    }

    public function destroy(Pembina $pembina): RedirectResponse
    {
        $pembina->delete();
        return redirect()->route('pembina.index')->with('success', 'Data pembina berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(['nama' => ['required', 'string', 'max:255'], 'no_hp' => ['required', 'string', 'max:255'], 'keahlian' => ['required', 'string', 'max:255']]);
    }
}
