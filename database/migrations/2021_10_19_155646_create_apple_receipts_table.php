<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppleReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('apple_receipts', static function (Blueprint $table) {
            $table->id();
            $table
                ->string('original_transaction_id')
                ->unique();
            $table
                ->string('currency')
                ->nullable();
            $table
                ->float('price')
                ->nullable();
            $table
                ->string('idfa')
                ->nullable();
            $table
                ->string('appsflyer_id')
                ->nullable();
            $table
                ->foreignId('user_id')
                ->nullable();
            $table
                ->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apple_receipts');
    }
}
