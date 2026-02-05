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
    Schema::table('bills', function (Blueprint $table) {
        $table->decimal('credit_amount', 10, 2)->default(0);
        $table->decimal('debit_amount', 10, 2)->default(0);
        $table->dropColumn('type');
    });
}

public function down()
{
    Schema::table('bills', function (Blueprint $table) {
        $table->string('type')->nullable();
        $table->dropColumn(['credit_amount', 'debit_amount']);
    });
}

};
