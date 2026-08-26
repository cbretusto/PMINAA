<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SystemOneHrisDepartment;
use App\Models\SystemOneHrisPosition;
use App\Models\SystemOneHrisSection;
use App\Models\SystemOneHrisDivision;

class SystemOneSubcon extends Model{
    protected $table = 'tbl_EmployeeInfo';
    protected $connection = 'mysql_systemone_subcon';

    public function department_info(){
        return $this->hasOne(SystemOneHrisDepartment::class, 'pkid', 'fkDepartment');
    }

    public function position_info(){
        return $this->hasOne(SystemOneHrisPosition::class, 'pkid', 'fkPosition');
    }

    public function section_info(){
        return $this->hasOne(SystemOneHrisSection::class, 'pkid', 'fkSection');
    }

    public function division_info(){
        return $this->hasOne(SystemOneHrisDivision::class, 'pkid', 'fkDivision');
    }
}
