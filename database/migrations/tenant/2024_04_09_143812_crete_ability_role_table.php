<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ability_role', function (Blueprint $table) {
            $table->foreignUlid('ability_id')->constrained(table: 'abilities');
            $table->foreignUlid('role_id')->constrained(table: 'roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_role');
    }
};
