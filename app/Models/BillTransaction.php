<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillTransaction extends Model
{
    protected $fillable = [
        'bill_id',
        'direction',
        'amount',
        'before_debit',
        'before_credit',
        'after_debit',
        'after_credit',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}

