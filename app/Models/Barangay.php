<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barangay extends Model
{
    protected $primaryKey = 'brgy_name';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'brgy_name',
        'population',
        'city',
        'household',
        'has_health_center',
    ];

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
