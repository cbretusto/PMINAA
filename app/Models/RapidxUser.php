<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserManagement;
use App\Models\SystemOneHrisEmployeeInfo;
use App\Models\RapidxAssignModule;

class RapidxUser extends Model{
    protected $table = "users";
    protected $connection = "mysql_rapidx";

    public function rapidx_systemone_employee_info(){
        // return $this->hasOne(SystemOneHrisEmployeeInfo::class, 'EmpNo', 'employee_number')->where('EmpStatus', 1)->whereIn('fkPosition', [1,2,3,4,7,8,10,12,75,102,103,121,124])->select(['EmpNo','fkDepartment','fkPosition']);
        return $this->hasOne(SystemOneHrisEmployeeInfo::class, 'EmpNo', 'employee_number')->where('EmpStatus', 1)->select(['EmpNo','fkDepartment','fkPosition', 'EmpStatus']);
    }

    public function pminaa_user_info(){
        return $this->hasOne(UserManagement::class, 'rapidx_user_id', 'id')->where('logdel', 0);
    }

    public function rapidx_assign_module_details(){
        return $this->hasMany(RapidxAssignModule::class, 'user_id', 'id');
    }
}
