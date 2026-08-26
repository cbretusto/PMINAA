<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessDetails extends Model
{
    protected $table = 'access_details';
    protected $connection = 'mysql';

    protected $fillable = [
        'accesses_id',
        'description',
        'updated_at'
    ];
}
