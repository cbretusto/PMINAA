<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RapidxUser;
use App\Models\PminaaApprover;

class PminaaDetails extends Model
{
    protected $table = 'pminaa_details';
    protected $connection = 'mysql';
    protected $fillable = [
            'requested_by',
            'user_type',
            'factory',
            'employee_no',
            'employee_name',
            'employee_middlename',
            'employee_lastname',
            'nature_of_employment',
            'department_agency',
            'position_job_title',
            'section',
            'division',
            'remarks',
            'internet_access',
            'account_system_access',
            'network_folder_access',
            'approval_status',
            'control_no',
            'status',
            'logdel',
            'created_at',
            'updated_at'
        ];
    public function rapidx_user_info(){
        return $this->hasOne(RapidxUser::class, 'id', 'requested_by');
    }

    public function approvers_info(){
        return $this->hasMany(PminaaApprover::class, 'pminaa_details_id', 'id');
    }
}
