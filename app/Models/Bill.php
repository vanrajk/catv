<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'customer_id',
        'bill_date',
        'bill_year',
        'bill_month',
        'credit_amount',
        'debit_amount',
    ];
 protected $casts = [
        'bill_date' => 'date',
        'credit_amount' => 'decimal:2',
        'debit_amount' => 'decimal:2',
    ];
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    /**
     * Relationship to bill transactions
     */
    public function transactions()
    {
        return $this->hasMany(BillTransaction::class);
    }
}
