<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Controllers
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\PminaaRequestController;

// Route::get('/', function () {
//     return view('user_management');
// })->name('user_management');

Route::get('/user_management', function () {
    return view('user_management');
})->name('user_management');

Route::get('/access', function () {
    return view('access');
})->name('access');

Route::get('/', function () {
    return view('pminaa_request');
})->name('pminaa_request');

Route::controller(UserManagementController::class)->group(function () {
    // USER MANAGEMNET
    Route::get('/view_user', 'viewUser')->name('view_user');
    Route::get('/get_rapidx_user_active_in_systemone', 'getRapidxUserActiveInSystemOne')->name('get_rapidx_user_active_in_systemone');
    Route::get('/get_systemone_department', 'getSystemOneDepartment')->name('get_systemone_department');
    Route::get('/get_systemone_position', 'getSystemOnePosition')->name('get_systemone_position');
    Route::post('/user_create_update', 'userCreateUpdate')->name('user_create_update');
    Route::get('/get_user_info_by_id', 'getUserInfoById')->name('get_user_info_by_id');
    Route::post('/change_user_status', 'changeUserStatus')->name('change_user_status');

    // USER APPROVER
    Route::get('/view_user_approver', 'viewUserApprover')->name('view_user_approver');
    Route::get('/get_info_from_user_management', 'getInfoFromUserManagement')->name('get_info_from_user_management');
    Route::post('/create_update_user_approver', 'createUpdateUserApprover')->name('create_update_user_approver');
    Route::get('/get_user_approver_info_by_id', 'getUserApproverInfoById')->name('get_user_approver_info_by_id');
    Route::post('/remove_user_approver', 'removeUserApprover')->name('remove_user_approver');
});

Route::controller(AccessController::class)->group(function () {
    Route::get('/view_user_access', 'viewUserAccess')->name('view_user_access');
    Route::post('/create_update_user_access', 'createUpdateUserAccess')->name('create_update_user_access');
    Route::get('/get_user_access_info_by_id', 'getUserAccessInfoById')->name('get_user_access_info_by_id');
    Route::post('/change_user_access_status', 'changeUserAccessStatus')->name('change_user_access_status');

    Route::get('/view_user_access_details', 'viewUserAccessDetails')->name('view_user_access_details');
    Route::post('/create_update_user_access_details', 'createUpdateUserAccessDetails')->name('create_update_user_access_details');
    Route::post('/change_access_details_status', 'changeAccessDetailsStatus')->name('change_access_details_status');
});

Route::controller(PminaaRequestController::class)->group(function () {
    Route::get('/view_pminaa_request', 'viewPminaaRequest')->name('view_pminaa_request');
    Route::get('/get_systemone_pmi_subcon_employee', 'getSystemonePmiSubconEmployee')->name('get_systemone_pmi_subcon_employee');
    Route::get('/get_employee_info', 'getEmployeeInfo')->name('get_employee_info');
    Route::get('/get_pminaa_approver', 'getPminaaApprover')->name('get_pminaa_approver');
    Route::get('/get_account_system_folder_access', 'getAccountSystemFolderAccess')->name('get_account_system_folder_access');
    Route::get('/get_account_system_folder_name', 'getAccountSystemFolderName')->name('get_account_system_folder_name');
    Route::post('/create_update_pminaa_request', 'createUpdatePminaaRequest')->name('create_update_pminaa_request');
    Route::get('/get_pminaa_request_info_by_id', 'getPminaaRequestInfoById')->name('get_pminaa_request_info_by_id');
    Route::post('/pminaa_request_change_approval_status', 'pminaaRequestChangeApprovalStatus')->name('pminaa_request_change_approval_status');
    Route::get('/view_pdf_pminaa_request/{id}', 'viewPdfPminaaRequest');
    Route::post('/approve_all_pending_requests', 'approveAllPendingRequests')->name('approve_all_pending_requests');
});
