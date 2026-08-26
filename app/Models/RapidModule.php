<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidModule extends Model
{
    protected $table = "tbl_module";
    protected $connection = "mysql_rapid_module";
}
