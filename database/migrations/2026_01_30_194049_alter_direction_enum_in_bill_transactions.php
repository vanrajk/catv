<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE bill_transactions
            MODIFY direction ENUM(
                'debit_to_credit',
                'credit_to_debit',
                'bill_created'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE bill_transactions
            MODIFY direction ENUM(
                'debit_to_credit',
                'credit_to_debit'
            ) NOT NULL
        ");
    }
};

