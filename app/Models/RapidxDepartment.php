<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapidxDepartment extends Model
{
    protected $table = "departments";
    protected $connection = "mysql_rapidx";
}
