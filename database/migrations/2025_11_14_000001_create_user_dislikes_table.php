<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('user_dislikes')) {
            Schema::create('user_dislikes', function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->unsignedBigInteger('foto_id');
                $table->timestamps();

                $table->foreign('foto_id')->references('id')->on('foto')->onDelete('cascade');
                $table->index(['user_id', 'foto_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_dislikes');
    }
};
