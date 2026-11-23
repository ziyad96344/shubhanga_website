<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = ['type', 'name', 'email', 'phone', 'data', 'status', 'ip'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
