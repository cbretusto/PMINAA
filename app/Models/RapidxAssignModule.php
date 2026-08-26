<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidxAssignModule extends Model
{
    protected $table = "user_accesses";
    protected $connection = "mysql_rapidx";
}
