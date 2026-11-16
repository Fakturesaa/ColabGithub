<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('ekskul', 'pembina_id')) {
            return;
        }

        Schema::table('ekskul', function (Blueprint $table) {
            $table->string('nama_pembina')->nullable();
        });

        DB::table('ekskul')
            ->join('users', 'ekskul.pembina_id', '=', 'users.id')
            ->select('ekskul.id', 'users.name')
            ->orderBy('ekskul.id')
            ->get()
            ->each(fn ($row) => DB::table('ekskul')->where('id', $row->id)->update(['nama_pembina' => $row->name]));

        Schema::table('ekskul', function (Blueprint $table) {
            $table->dropForeign(['pembina_id']);
            $table->dropColumn('pembina_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('ekskul', 'nama_pembina')) {
            return;
        }

        Schema::table('ekskul', function (Blueprint $table) {
            $table->foreignId('pembina_id')->nullable()->constrained('users');
        });

        DB::table('ekskul')
            ->join('users', 'ekskul.nama_pembina', '=', 'users.name')
            ->select('ekskul.id', 'users.id as user_id')
            ->orderBy('ekskul.id')
            ->get()
            ->each(fn ($row) => DB::table('ekskul')->where('id', $row->id)->update(['pembina_id' => $row->user_id]));

        Schema::table('ekskul', function (Blueprint $table) {
            $table->dropColumn('nama_pembina');
        });
    }
};
