<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserManagementRequest extends FormRequest{
    public function authorize(){
        // Set to true if you want all users to access this request,
        // or implement your authorization logic here.
        return true;
    }
    

    public function rules(){
        $action = $this->route()->getActionMethod();

        if ($action === 'userCreateUpdate') {
            return [
                'name_w_id'     => 'required|string',
                'user_level'    => 'required|in:0,1',
                'employee_no'   => 'required',
                'email'         => 'required',
                'department'    => 'required',
                'position'      => 'required',
            ];
        } 
        
        if ($action === 'changeUserStatus') {
            return [
                'user_id' => 'required',
                'status'  => 'required|in:0,1',
            ];
        }

        if ($action === 'createUpdateUserApprover') {
            return [
                'user_id'                      => 'required',
                'user_approver_classification'   => 'required|array|min:1',
                'user_approver_classification.*' => 'in:1,2,3,4,5',            
            ];
        }

        return [];
    }

    public function messages(){
        $action = $this->route()->getActionMethod();

        if ($action === 'userCreateUpdate') {
            return [
                'name_w_id.required'    => 'Name is required.',
                'user_level.required'   => 'User level is required.',
                'employee_no.required'  => 'Name is required.',
                'email.required'        => 'Name is required.',
                'user_level.in'         => 'User level must be either 0 (Admin) or 1 (User).',
                'department.required'   => 'Department is required.',
                'position.required'     => 'Position is required.',
            ];
        } 
        
        if ($action === 'changeUserStatus') {
            return [
                'user_id.required' => 'User ID is required.',
                'user_id.exists'   => 'User not found.',
                'status.required'  => 'Status is required.',
                'status.in'        => 'Invalid status value.',
            ];
        }

        if ($action === 'createUpdateUserApprover') {
            return [
                'user_id.required'                      => 'User approver ID is required.',
                'user_id.exists'                        => 'User approver not found.',
                'user_approver_classification.required'   => 'At least one classification is required.',
                'user_approver_classification.array'      => 'Classification must be an array.',
                'user_approver_classification.min'        => 'Please select at least one classification.',
                'user_approver_classification.*.in'       => 'Invalid classification value.',
            ];
        }

        return [];
    }
}
