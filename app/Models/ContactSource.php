<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSource extends Model
{
    protected $fillable = [
        'origin', 'recipient', 'is_active', 'email_subject', 'email_eyebrow',
        'email_heading', 'email_intro', 'email_footer', 'email_header_image_path',
        'email_logo_path', 'email_header_color', 'email_accent_color', 'email_background_color',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
