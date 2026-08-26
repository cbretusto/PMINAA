<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'accesses';
    protected $connection = 'mysql';
    protected $fillable = [
        'description',
        'category',
        'details',
        'updated_at'
    ];
}
