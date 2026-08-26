<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidxModule extends Model
{
    protected $table = "modules";
    protected $connection = "mysql_rapidx";
}
