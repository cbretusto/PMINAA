<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RapidAssignModule;

class RapidUser extends Model
{
    protected $table = "tbl_useraccounts";
    protected $connection = "mysql_rapid_module";
    public $timestamps = false;

    public function rapid_assign_module_details(){
        return $this->hasMany(RapidAssignModule::class, 'username', 'id')->where('logdel', 0);
    }

}
