<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
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
