<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('bill_transactions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('bill_id')
              ->constrained('bills')
              ->cascadeOnDelete();

        $table->enum('direction', [
            'debit_to_credit',
            'credit_to_debit'
        ]);

        $table->decimal('amount', 10, 2);

        $table->decimal('before_debit', 10, 2);
        $table->decimal('before_credit', 10, 2);

        $table->decimal('after_debit', 10, 2);
        $table->decimal('after_credit', 10, 2);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('bill_transactions');
}

};
