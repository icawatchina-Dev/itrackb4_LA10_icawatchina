<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barangay extends Model
{
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'city', 'name');
    }
    protected $table = 'brgys';

    protected function casts(): array
    {
        return [
            'population' => 'integer',
            'household' => 'integer',
            'has_health_center' => 'boolean',
        ];
    }
}
