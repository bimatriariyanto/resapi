<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuaca', function (Blueprint $table) {
            $table->id();
            $table->string('kec_id')->nullable();
            $table->datetime('date')->nullable();
            $table->string('tmin')->nullable();
            $table->string('tmax')->nullable();
            $table->string('humin')->nullable();
            $table->string('humax')->nullable();
            $table->string('hu')->nullable();
            $table->string('t')->nullable();
            $table->string('weather')->nullable();
            $table->string('wd')->nullable();
            $table->string('ws')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuaca');
    }
};
