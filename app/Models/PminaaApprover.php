<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RapidxUser;

class PminaaApprover extends Model
{
    protected $table = 'pminaa_approvers';
    protected $connection = 'mysql';

    public function section_head_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'section_head');
    }
    public function department_head_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'department_head');
    }
    public function iss_manager_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'iss_manager');
    }
    public function admin_avp_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'admin_avp');
    }
    public function iss_hardware_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'iss_hardware');
    }
}
