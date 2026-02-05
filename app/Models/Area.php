<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = ['zone_id', 'name'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
