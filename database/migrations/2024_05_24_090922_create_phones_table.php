<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulidMorphs('phoneable');
            $table->string('type');
            $table->string('ddi');
            $table->string('ddd');
            $table->string('number');
            $table->string('full_phone')->virtualAs('CONCAT("+",ddi, " (", ddd, ") ", number)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
