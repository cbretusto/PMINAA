<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemOneAclAssignModule;
use Illuminate\Database\Eloquent\Model;

class SystemOneAclUser extends Model
{
    protected $table = "tbl_useraccnt";
    protected $connection = "mysql_systemone_module";
    public $timestamps = false;

    public function systemone_assign_module_details(){
        return $this->hasMany(SystemOneAclAssignModule::class, 'fkuser', 'pkid')->where('logdel', 0);
    }
}
