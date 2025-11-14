<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('foto', function (Blueprint $table) {
            if (!Schema::hasColumn('foto', 'dislikes')) {
                $table->unsignedInteger('dislikes')->default(0)->after('likes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('foto', function (Blueprint $table) {
            if (Schema::hasColumn('foto', 'dislikes')) {
                $table->dropColumn('dislikes');
            }
        });
    }
};
