<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest
{
    public function authorize(){
        // Set to true if you want all users to access this request,
        // or implement your authorization logic here.
        return true;
    }
    

    public function rules(){
        $action = $this->route()->getActionMethod();

        if ($action === 'createUpdateUserAccess') {
            return [
                'category'      => 'required',
                'description'    => 'required',
                'details'    => 'required',
            ];
        }

        if ($action === 'changeUserAccessStatus') {
            return [
                'user_access_id' => 'required',
                'status'  => 'required|in:0,1',
            ];
        }

        if ($action === 'createUpdateUserAccessDetails') {
            return [
                'description'      => 'required',
            ];
        }

        if ($action === 'changeUserAccessDetailsStatus') {
            return [
                'user_access_details_id' => 'required',
                'status'  => 'required|in:0,1',
            ];
        }
        return [];
    }

    public function messages(){
        $action = $this->route()->getActionMethod();

        if ($action === 'createUpdateUserAccess') {
            return [
                'category.required'     => 'Category is required.',
                'description.required'   => 'Description is required.',
                'details.required'       => 'Details are required.',
            ];
        }

        if ($action === 'changeUserAccessStatus') {
            return [
                'user_access_id.required' => 'User Access ID is required.',
                'user_access_id.exists'   => 'User Access not found.',
                'status.required'         => 'Status is required.',
                'status.in'               => 'Invalid status value.',
            ];
        }

        if ($action === 'createUpdateUserAccessDetails') {
            return [
                'description.required'   => 'Description is required.',
            ];
        }

        if ($action === 'changeUserAccessStatus') {
            return [
                'user_access_id.required' => 'User Access ID is required.',
                'user_access_id.exists'   => 'User Access not found.',
                'status.required'         => 'Status is required.',
                'status.in'               => 'Invalid status value.',
            ];
        }
        return [];
    }
}
