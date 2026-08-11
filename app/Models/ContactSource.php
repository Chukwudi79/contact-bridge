<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSource extends Model
{
    protected $fillable = ['origin', 'recipient', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
