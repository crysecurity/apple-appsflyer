<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplePurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('apple_purchases', static function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('apple_receipt_id');
            $table->boolean('is_trial');
            $table
                ->boolean('sent_to_af')
                ->default(false)
                ->comment('Has it been sent to the "AppsFlyer"');
            $table->timestamps();

            $table
                ->foreign('apple_receipt_id')
                ->references('id')
                ->on('apple_receipts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('apple_purchases');
    }
}
