<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    use SoftDeletes;
protected $table = 'customers';

    protected $fillable = [
        'customer_number',
        'name',
        'account_number',
        'mobile',
        'site_id',
        'zone_id',
        'area_id',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
public function bills()
{
    return $this->hasMany(Bill::class, 'customer_id');
}

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
