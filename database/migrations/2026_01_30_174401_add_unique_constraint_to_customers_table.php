<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add unique constraint for zone_id + area_id + customer_number
            $table->unique(['zone_id', 'area_id', 'customer_number'], 'unique_customer_per_zone_area');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('unique_customer_per_zone_area');
        });
    }
};