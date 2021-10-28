<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToApplePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('apple_purchases', static function (Blueprint $table) {
            $table->timestamp('expires_date')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->string('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('apple_purchases', static function (Blueprint $table) {
            $table->dropColumn(['expires_date', 'purchase_date', 'product_id']);
        });
    }
}
