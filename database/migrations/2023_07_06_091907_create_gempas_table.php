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
        Schema::create('gempas', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->string('coordinates');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('magnitude');
            $table->string('depth');
            $table->string('area');
            $table->string('potential');
            $table->string('subject');
            $table->string('headline');
            $table->text('description');
            $table->string('instruction');
            $table->text('felt');
            $table->string('shakemap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gempas');
    }
};
