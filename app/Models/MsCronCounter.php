<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsCronCounter extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'offset',
        'total',
        'limit',
        'updated_at'
    ];

    // Scopes
    public function scopeImportProducts($query)
    {
        return $query->where('action', 'import_products');
    }

    // Reset products import counters
    public function resetImportProducts()
    {
        $this->offset = 0;
        $this->limit = 0;
        return $this->save();
    }
}
