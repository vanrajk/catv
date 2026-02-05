<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->year('bill_year');          // 2025, 2026
            $table->tinyInteger('bill_month');  // 1 = Jan, 12 = Dec

            $table->date('bill_date')->nullable();

            $table->decimal('amount', 10, 2);

            $table->enum('type', ['credit', 'debit'])
                  ->default('debit');

            $table->timestamps();
            $table->softDeletes();

            // prevent duplicate month billing
            $table->unique(
                ['customer_id', 'bill_year', 'bill_month'],
                'unique_customer_month'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
