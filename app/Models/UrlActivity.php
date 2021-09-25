<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlActivity extends Model
{
    use HasFactory;

    public $timestamps = ["created_at", "updated_at"];

    protected $fillable = [
        'url_id',
        'ip',
        'type',
    ];
}
