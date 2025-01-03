<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignUlid('role_id')->constrained(table: 'roles');
            $table->foreignUlid('user_id')->constrained(table: 'users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
