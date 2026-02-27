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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->date('date');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2)->unsigned();
            $table->decimal('balance_before', 15, 2)->unsigned();
            $table->decimal('balance_after', 15, 2)->unsigned();
            $table->string('description');
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
