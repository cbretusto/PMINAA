<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemOneAclSystem extends Model
{

    protected $table = "tbl_system";
    protected $connection = "mysql_systemone_module";
}
