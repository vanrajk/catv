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
        Schema::table('customers', function (Blueprint $table) {
            // ✅ Index for search fields (name, account_number, mobile, customer_number)
            $table->index('name', 'idx_customers_name');
            $table->index('account_number', 'idx_customers_account_number');
            $table->index('mobile', 'idx_customers_mobile');
            $table->index('customer_number', 'idx_customers_customer_number');
            
            // ✅ Index for filter fields (zone_id, area_id, site_id)
            $table->index('zone_id', 'idx_customers_zone_id');
            $table->index('area_id', 'idx_customers_area_id');
            $table->index('site_id', 'idx_customers_site_id');
            
            // ✅ Composite index for zone + area (used for unique customer_number check)
            $table->index(['zone_id', 'area_id'], 'idx_customers_zone_area');
            
            // ✅ Composite index for zone + area + customer_number (for faster lookups)
            $table->index(['zone_id', 'area_id', 'customer_number'], 'idx_customers_zone_area_number');
            
            // ✅ Full-text index for better text search (optional, for MySQL 5.6+)
            // Uncomment if you want to use FULLTEXT search
            // $table->fullText(['name', 'account_number', 'mobile'], 'idx_customers_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_customers_name');
            $table->dropIndex('idx_customers_account_number');
            $table->dropIndex('idx_customers_mobile');
            $table->dropIndex('idx_customers_customer_number');
            $table->dropIndex('idx_customers_zone_id');
            $table->dropIndex('idx_customers_area_id');
            $table->dropIndex('idx_customers_site_id');
            $table->dropIndex('idx_customers_zone_area');
            $table->dropIndex('idx_customers_zone_area_number');
            
            // If you used fulltext
            // $table->dropFullText('idx_customers_fulltext');
        });
    }
};