<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $ekskulPembinaForeign = collect(Schema::getForeignKeys('ekskul'))
            ->first(fn (array $key) => $key['columns'] === ['pembina_id']);

        if (Schema::hasColumn('ekskul', 'nama_pembina') || ($ekskulPembinaForeign && $ekskulPembinaForeign['foreign_table'] !== 'pembina')) {
            Schema::table('ekskul', function (Blueprint $table) {
                $table->unsignedBigInteger('pembina_ref')->nullable();
            });

            if (Schema::hasColumn('ekskul', 'nama_pembina')) {
                $rows = DB::table('ekskul')->select('id', 'nama_pembina')->get();

                foreach ($rows as $row) {
                    if (! $row->nama_pembina) {
                        continue;
                    }

                    $pembina = DB::table('pembina')->where('nama', $row->nama_pembina)->first();
                    $pembinaId = $pembina?->id ?? DB::table('pembina')->insertGetId([
                        'nama' => $row->nama_pembina,
                        'no_hp' => '',
                        'keahlian' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('ekskul')->where('id', $row->id)->update(['pembina_ref' => $pembinaId]);
                }
            } elseif ($ekskulPembinaForeign && $ekskulPembinaForeign['foreign_table'] === 'users') {
                $rows = DB::table('ekskul')->join('users', 'ekskul.pembina_id', '=', 'users.id')
                    ->select('ekskul.id', 'users.name')->get();

                foreach ($rows as $row) {
                    $pembina = DB::table('pembina')->where('nama', $row->name)->first();
                    $pembinaId = $pembina?->id ?? DB::table('pembina')->insertGetId([
                        'nama' => $row->name,
                        'no_hp' => '',
                        'keahlian' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('ekskul')->where('id', $row->id)->update(['pembina_ref' => $pembinaId]);
                }
            }

            Schema::table('ekskul', function (Blueprint $table) use ($ekskulPembinaForeign) {
                if ($ekskulPembinaForeign) {
                    $table->dropForeign(['pembina_id']);
                    $table->dropColumn('pembina_id');
                }
                if (Schema::hasColumn('ekskul', 'nama_pembina')) {
                    $table->dropColumn('nama_pembina');
                }
                $table->renameColumn('pembina_ref', 'pembina_id');
            });

            Schema::table('ekskul', function (Blueprint $table) {
                $table->foreign('pembina_id')->references('id')->on('pembina');
            });
        }

        $pendaftaranSiswaForeign = collect(Schema::getForeignKeys('pendaftaran'))
            ->first(fn (array $key) => $key['columns'] === ['siswa_id']);

        if ($pendaftaranSiswaForeign && $pendaftaranSiswaForeign['foreign_table'] !== 'siswa') {
            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->unsignedBigInteger('siswa_ref')->nullable();
            });

            $rows = DB::table('pendaftaran')->join('users', 'pendaftaran.siswa_id', '=', 'users.id')
                ->select('pendaftaran.id', 'users.name')->get();

            foreach ($rows as $row) {
                $siswa = DB::table('siswa')->where('nama', $row->name)->orderBy('id')->first();
                if ($siswa) {
                    DB::table('pendaftaran')->where('id', $row->id)->update(['siswa_ref' => $siswa->id]);
                }
            }

            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->dropForeign(['siswa_id']);
                $table->dropColumn('siswa_id');
                $table->renameColumn('siswa_ref', 'siswa_id');
            });

            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->foreign('siswa_id')->references('id')->on('siswa');
            });
        }
    }

    public function down(): void
    {
        $ekskulForeign = collect(Schema::getForeignKeys('ekskul'))
            ->first(fn (array $key) => $key['columns'] === ['pembina_id']);

        if ($ekskulForeign && $ekskulForeign['foreign_table'] === 'pembina') {
            Schema::table('ekskul', function (Blueprint $table) {
                $table->string('nama_pembina')->nullable();
            });

            DB::table('ekskul')->join('pembina', 'ekskul.pembina_id', '=', 'pembina.id')
                ->select('ekskul.id', 'pembina.nama')->get()
                ->each(fn ($row) => DB::table('ekskul')->where('id', $row->id)->update(['nama_pembina' => $row->nama]));

            Schema::table('ekskul', function (Blueprint $table) {
                $table->dropForeign(['pembina_id']);
                $table->dropColumn('pembina_id');
            });
        }

        $pendaftaranForeign = collect(Schema::getForeignKeys('pendaftaran'))
            ->first(fn (array $key) => $key['columns'] === ['siswa_id']);

        if ($pendaftaranForeign && $pendaftaranForeign['foreign_table'] === 'siswa') {
            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->unsignedBigInteger('user_ref')->nullable();
            });

            DB::table('pendaftaran')->join('siswa', 'pendaftaran.siswa_id', '=', 'siswa.id')
                ->join('users', 'siswa.nama', '=', 'users.name')
                ->select('pendaftaran.id', 'users.id as user_id')->get()
                ->each(fn ($row) => DB::table('pendaftaran')->where('id', $row->id)->update(['user_ref' => $row->user_id]));

            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->dropForeign(['siswa_id']);
                $table->dropColumn('siswa_id');
                $table->renameColumn('user_ref', 'siswa_id');
            });

            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->foreign('siswa_id')->references('id')->on('users');
            });
        }
    }
};
