<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raks', function (Blueprint $table) {
            $table->year('year')->after('name');
            $table->index('year');
        });

        DB::table('raks')->update(['year' => date('Y')]);
    }

    public function down(): void
    {
        Schema::table('raks', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};