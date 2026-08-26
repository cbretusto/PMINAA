<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemOneAclModule extends Model
{
    protected $table = "tbl_modules";
    protected $connection = "mysql_systemone_module";
}
