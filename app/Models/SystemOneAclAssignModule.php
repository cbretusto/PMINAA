<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemOneAclAssignModule extends Model
{
    protected $table = "tbl_acl";
    protected $connection = "mysql_systemone_module";
}
