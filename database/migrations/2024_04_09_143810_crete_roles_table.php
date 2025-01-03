<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained(table: 'tenants', column: 'id')->cascadeOnDelete();
            $table->string('name', 100);
            $table->tinyInteger('hierarchy');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');

    }
};
