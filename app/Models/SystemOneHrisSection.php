<?php

namespace App\Models;

use App\Models\SystemOneHrisDepartment;
use Illuminate\Database\Eloquent\Model;

class SystemOneHrisSection extends Model
{
    protected $table = 'tbl_Section';
    protected $connection = 'mysql_systemone';
}
