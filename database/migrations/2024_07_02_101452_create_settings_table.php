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
        Schema::create('settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained(table: 'tenants', column: 'id')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('cnpj')->nullable();
            $table->date('opened_in')->nullable();
            $table->string('about')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('x')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->decimal('interest_rate_sale', 10, 2)->nullable();
            $table->decimal('interest_rate_installment', 10, 2)->nullable();
            $table->decimal('late_fee', 10, 2)->nullable();

            $table->string('font_family')->nullable();
            $table->string('font_color')->nullable()->default('white');
            $table->string('promo_price_color')->nullable()->default('green');
            $table->string('body_bg_color')->nullable()->default('#64748b');
            $table->string('card_color')->nullable()->default('#475569');
            $table->string('card_text_color')->nullable()->default('white');
            $table->string('nav_color')->nullable()->default('black');
            $table->string('footer_color')->nullable()->default('black');
            $table->string('footer_text_color')->nullable()->default('white');
            $table->string('link_color')->nullable()->default('green');
            $table->string('link_text_color')->nullable()->default('white');
            $table->string('btn_1_color')->nullable()->default('#6467f7');
            $table->string('btn_1_text_color')->nullable()->default('white');
            $table->string('btn_2_color')->nullable()->default('#e03f3f');
            $table->string('btn_2_text_color')->nullable()->default('white');
            $table->string('btn_3_color')->nullable()->default('#50a84f');
            $table->string('btn_3_text_color')->nullable()->default('white');
            $table->string('select_color')->nullable()->default('#6467f7');
            $table->string('select_text_color')->nullable()->default('white');
            $table->string('check_color')->nullable()->default('#6467f7');
            $table->string('check_text_color')->nullable()->default('white');
            $table->string('bg_img')->nullable();
            $table->string('bg_img_opacity')->nullable()->default('0.3');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
