<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PminaaRequestRequest extends FormRequest
{
    public function authorize(){
        // Set to true if you want all users to access this request,
        // or implement your authorization logic here.
        return true;
    }
    

    public function rules(){
        $action = $this->route()->getActionMethod();

        if ($action === 'viewPminaaRequest') {
            return [
                'category'      => 'required',
                'description'    => 'required',
                'details'    => 'required',
            ];
        }

        if ($action === 'pminaaRequestChangeApprovalStatus') {
            return [
                'pminaa_id' => 'required',
                'approval_status'  => 'required|in:0,1',
            ];
        }


        return [];
    }

    public function messages(){
        $action = $this->route()->getActionMethod();

        if ($action === 'viewPminaaRequest') {
            return [
                'category.required'     => 'Category is required.',
                'description.required'   => 'Description is required.',
                'details.required'       => 'Details are required.',
            ];
        }

        if ($action === 'pminaaRequestChangeApprovalStatus') {
            return [
                'pminaa_id.required' => 'User Access ID is required.',
                'pminaa_id.exists'   => 'User Access not found.',
                'approval_status.required'         => 'Status is required.',
                'approval_status.in'               => 'Invalid status value.',
            ];
        }

        return [];
    }
}
