@extends('layouts.app')
@section('title','Ekstrakurikuler')@section('section','Ekstrakurikuler')
@section('content')
<div class="page-head"><div><div class="eyebrow">KEGIATAN SEKOLAH</div><h1>Ekstrakurikuler</h1><p>Atur program, jadwal latihan, dan kapasitas peserta.</p></div><a class="btn btn-primary" href="{{ route('ekskul.create') }}">＋ Tambah ekskul</a></div>
<div class="stats">
    <div class="stat"><span class="stat-icon">◈</span><div class="stat-label">Total ekskul</div><div class="stat-value">{{ $totalEkskul }}</div><div class="stat-note">Program aktif tercatat</div></div>
    <div class="stat"><span class="stat-icon">▤</span><div class="stat-label">Pendaftaran</div><div class="stat-value">{{ $totalPendaftaran }}</div><div class="stat-note">Total peserta terdaftar</div></div>
    <div class="stat"><span class="stat-icon">♙</span><div class="stat-label">Pilihan hari</div><div class="stat-value">{{ $items->pluck('hari_latihan')->unique()->count() }}</div><div class="stat-note">Hari latihan di halaman ini</div></div>
    <div class="stat"><span class="stat-icon">✦</span><div class="stat-label">Pembina terdaftar</div><div class="stat-value">{{ $pembinaCount }}</div><div class="stat-note">Di tabel pembina</div></div>
</div>
<section class="panel"><div class="panel-head"><h2>Daftar ekstrakurikuler</h2><span>{{ $items->total() }} program</span></div>
<div class="table-wrap"><table><thead><tr><th>Nama kegiatan</th><th>Pembina</th><th>Hari latihan</th><th>Kuota</th><th>Pendaftar</th><th>Aksi</th></tr></thead><tbody>
@forelse($items as $item)<tr><td><div class="person"><span class="mini-avatar">{{ mb_substr($item->nama_ekskul,0,1) }}</span><span><strong>{{ $item->nama_ekskul }}</strong><br><span class="muted">ID #{{ $item->id }}</span></span></div></td><td><strong>{{ $item->pembina?->nama ?? '—' }}</strong></td><td>{{ $item->hari_latihan }}</td><td><span class="badge">{{ $item->kuota }} siswa</span></td><td><strong>{{ $item->diterima_count }}</strong> / {{ $item->kuota }} diterima</td><td><div class="actions"><a class="action" href="{{ route('ekskul.show',$item) }}">Detail</a><a class="action" href="{{ route('ekskul.edit',$item) }}">Edit</a><form class="inline-form" method="POST" action="{{ route('ekskul.destroy',$item) }}" onsubmit="return confirm('Hapus ekstrakurikuler ini?')">@csrf @method('DELETE')<button class="action delete">Hapus</button></form></div></td></tr>
@empty<tr><td colspan="6"><div class="empty"><strong>Belum ada ekstrakurikuler</strong>Tambahkan kegiatan pertama untuk mulai mengelola program sekolah.<br><br><a class="btn btn-primary" href="{{ route('ekskul.create') }}">＋ Tambah ekskul</a></div></td></tr>@endforelse
</tbody></table></div><div class="pagination">{{ $items->links() }}</div></section>
@endsection
