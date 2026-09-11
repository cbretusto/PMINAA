<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PDF;

use App\Models\PminaaDetails;
use App\Models\PminaaApprover;
use App\Models\SystemOneSubcon;
use App\Models\UserManagement;
use App\Models\Access;
use App\Models\AccessDetails;

use App\Models\SystemOneHrisEmployeeInfo;
use App\Models\SystemOneAclUser;
use App\Models\SystemOneAclModule;
use App\Models\SystemOneAclAssignModule;
use App\Models\SystemOneAclSystem;

use App\Models\RapidUser;
use App\Models\RapidModule;
use App\Models\RapidAssignModule;

use App\Models\RapidxUser;
use App\Models\RapidxModule;
use App\Models\RapidxAssignModule;

use App\Interfaces\PminaaRequestInterface;

class PminaaRequestRepository implements PminaaRequestInterface
{
    public function getAllPminaaRequestDataRepository($request, $rapidxUserId, $rapidxDepartmentId, array $conformanceApprovalIds){
        return PminaaDetails::with([
            'rapidx_user_info',
            'approvers_info.section_head_info',
            'approvers_info.department_head_info',
            'approvers_info.iss_manager_info',
            'approvers_info.admin_avp_info',
            'approvers_info.iss_hardware_info',
        ])
        ->where('logdel', 0)
        ->orderBy('control_no', 'desc')
        ->when(
            $request->status === 'requestor',
            function ($query) use ($rapidxUserId) {
                $query->where('requested_by', $rapidxUserId);
            }
        )
        ->when(
            $request->status === 'for_approval',
            function ($query) use ($rapidxUserId) {
                $query->where(function ($q) use ($rapidxUserId) {
                    // Status 0 = Section Head
                    $q->where(function ($statusQuery) use ($rapidxUserId) {
                        $statusQuery
                            ->where('approval_status', 0)
                            ->whereHas('approvers_info', function ($approver) use ($rapidxUserId) {
                                $approver->where('section_head', $rapidxUserId);
                            });
                    })

                    // Status 1 = Department Head
                    ->orWhere(function ($statusQuery) use ($rapidxUserId) {
                        $statusQuery
                            ->where('approval_status', 1)
                            ->whereHas('approvers_info', function ($approver) use ($rapidxUserId) {
                                $approver->where('department_head', $rapidxUserId);
                            });
                    })

                    // Status 2 = ISS Manager
                    ->orWhere(function ($statusQuery) use ($rapidxUserId) {
                        $statusQuery
                            ->where('approval_status', 2)
                            ->whereHas('approvers_info', function ($approver) use ($rapidxUserId) {
                                $approver->where('iss_manager', $rapidxUserId);
                            });
                    })

                    // Status 3 = Admin AVP
                    ->orWhere(function ($statusQuery) use ($rapidxUserId) {
                        $statusQuery
                            ->where('approval_status', 3)
                            ->whereHas('approvers_info', function ($approver) use ($rapidxUserId) {
                                $approver->where('admin_avp', $rapidxUserId);
                            });
                    });
                });
            }
        )
        ->when(
            $request->status === 'accountSetup',
            function ($query) use ($rapidxUserId) {
                $query
                    ->where('approval_status', 4)
                    ->whereHas('approvers_info', function ($approver) use ($rapidxUserId) {
                        $approver->where('iss_hardware', $rapidxUserId);
                    });
            }
        )
        ->when(
            $request->status === 'approved',
            function ($query) use ($rapidxUserId, $rapidxDepartmentId) {
                $query->where('approval_status', 5);

                if (!in_array($rapidxDepartmentId, [1, 2])) {
                    $query->where('requested_by', $rapidxUserId);
                }
            }
        )
        ->when(
            $request->status === 'disapproved',
            function ($query) use ($rapidxUserId, $rapidxDepartmentId) {
                $query->whereIn('approval_status', [6, 7, 8, 9, 10]);

                if (!in_array($rapidxDepartmentId, [1, 2])) {
                    $query->where('requested_by', $rapidxUserId);
                }
            }
        )
        ->when(
            $request->status === 'all',
            function ($query) use ($rapidxDepartmentId) {
                if (!in_array($rapidxDepartmentId, [1, 2])) {
                    $query->whereRaw('1 = 0');
                }
            }
        )
        ->when(
            !$request->filled('status') ||
            !in_array($request->status, [
                'requestor',
                'for_approval',
                'accountSetup',
                'approved',
                'disapproved',
                'all',
            ]),
            function ($query) {
                $query->whereRaw('1 = 0');
            }
        )
        ->get();
    }

    public function getSystemonePmiSubconEmployeeRepository($user_type){
        if($user_type == 'PMI'){
            return SystemOneHrisEmployeeInfo::where('EmpStatus', 1)->orderBy('EmpNo','ASC')->get(['EmpNo']);
        }

        if($user_type == 'SUBCON'){
            return SystemOneSubcon::where('EmpStatus', 1)->orderBy('EmpNo','ASC')->get(['EmpNo']);
        }
    }

    public function getEmployeeInfoRepository($employee_no, $user_type){
        if($user_type == 'PMI'){
            return  SystemOneHrisEmployeeInfo::with('department_info', 'position_info', 'section_info', 'division_info')
                ->where('EmpNo', $employee_no)
                ->where('EmpStatus', 1)
                ->get(['EmpNo','FirstName', 'LastName', 'MiddleName', 'fkDepartment', 'fkPosition', 'fkSection', 'fkDivision']);
        }

        if($user_type == 'SUBCON'){
            return SystemOneSubcon::with('department_info', 'position_info', 'section_info', 'division_info')
                ->where('EmpNo', $employee_no)
                ->where('EmpStatus', 1)
                ->get(['EmpNo','FirstName', 'LastName', 'MiddleName', 'fkDepartment', 'fkPosition', 'fkSection', 'fkDivision']);
        }
    }

    public function getPminaaApproverRepository(){
        return
            UserManagement::with('user_management_rapidx_user_info',)
            ->where('status', 0)
            ->where('logdel', 0)
            ->whereNotNull('classification')
            ->get();
    }

    public function getAccountSystemFolderAccessRepository(){
        return
            Access::where('status', 0)
            ->where('logdel', 0)
            ->orderBy('description', 'ASC')
            ->get();
    }

    public function getAccountSystemFolderNameRepository($get_access_id, $get_system_module){
        $data = collect();

        if ($get_system_module == 'SystemOne') {
            $data = SystemOneAclModule::where('logdel', 0)
                ->orderBy('modname', 'ASC')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->pkid,
                        'text' => $item->modname
                    ];
                });
        } elseif ($get_system_module == 'Rapid') {
            $data = RapidModule::where('logdel', 0)
                ->orderBy('moduleName', 'ASC')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->pkid,
                        'text' => $item->moduleName
                    ];
                });
        } elseif ($get_system_module == 'RapidX') {
            $data = RapidxModule::where('module_stat', 1)
                ->orderBy('module_name', 'ASC')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->module_id,
                        'text' => $item->module_name
                    ];
                });
        }else{
            $data = AccessDetails::where('accesses_id', $get_access_id)
                ->where('status', 0)
                ->where('logdel', 0)
                ->orderBy('description', 'ASC')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->description,
                        'text' => $item->description
                    ];
                });
        }

        return $data;
    }

    public function createUpdatePminaaRequestRepository(?string $pminaaId, array $data, $rapidx_user_id): bool{
        $pminaaData         = $this->mapPminaaData($data);
        $pminaaDataForEmail = $this->mapPminaaDataForEmail($data);
        $approverData       = $this->mapApproverData($data);
        $controlNo          = $this->mapPminaaControlNo();
        $requestedBy        = $pminaaData['requested_by'];
        $approvalStatus     = 0;
        $remarkValue        = '';
        // dd($pminaaData);
        if (empty($pminaaId)) {
            if(empty($controlNo)) {
                return false;
            }else{
                $getControlNo = $controlNo;
                $pminaaData['control_no'] = $getControlNo;
                $pminaaData['created_at'] = now();
                $getPminaaId = PminaaDetails::insertGetId($pminaaData);

                $approverData['pminaa_details_id']  = $getPminaaId;
                $approverData['created_at']         = now();
                PminaaApprover::insert($approverData);
            }
        }
        else {
            $pminaaData['updated_at'] = now();
            $pminaaData['approval_status'] = 0;
            $pminaa = PminaaDetails::findOrFail($pminaaId);
            $getControlNo = $pminaa['control_no'] ?? '';

            if(empty($getControlNo)) {
                return false;
            }else{
                $pminaa->update($pminaaData);

                $approverData['updated_at']                         =   now();
                $approverData['section_head_approved_by_date']      =   NULL;
                $approverData['section_head_approved_by_remark']    =   '';
                $approverData['department_head_approved_by_date']   =   NULL;
                $approverData['department_head_approved_by_remark'] =   '';
                $approverData['iss_manager_approved_by_date']       =   NULL;
                $approverData['iss_manager_approved_by_remark']     =   '';
                $approverData['admin_avp_approved_by_date']         =   NULL;
                $approverData['admin_avp_approved_by_remark']       =   '';
                $approverData['iss_hardware']                       =   NULL;
                $approverData['iss_hardware_approved_by_date']      =   NULL;
                $approverData['iss_hardware_approved_by_remark']    =   '';

                PminaaApprover::where('pminaa_details_id', $pminaaId)->update($approverData);
            }
        }
        // dd($getControlNo);
        $requestedBy = RapidxUser::where('id', $requestedBy)
        ->where('user_stat', 1)
        ->get('email');

        $pminaaDataForEmail['approved'] = 'ISS Hardware';
        $pminaaDataForEmail['control_no'] = $getControlNo;
        $this->mapEmailNotification($pminaaDataForEmail, $requestedBy, $approvalStatus, $approverData, $rapidx_user_id, $remarkValue); // CHAN
        return true;
    }

    private function mapPminaaControlNo(){
        $getLastControlNo   = PminaaDetails::orderBy('control_no', 'DESC')->where('logdel', 0)->where('status', 0)->first();

        $controlNoFormat    = "ISS-NAF-".NOW()->format('ym')."-";
        if ($getLastControlNo == null){
            $newControlNo   = $controlNoFormat.'001';
        }elseif(explode('-',$getLastControlNo->control_no)[2] != NOW()->format('ym')){
            $newControlNo   = $controlNoFormat.'001';
        }else{
            $explode_control_no = explode("-",  $getLastControlNo->control_no);
            $stringPad          = str_pad($explode_control_no[3]+1,3,"0",STR_PAD_LEFT);
            $newControlNo       = $controlNoFormat.$stringPad;
        }
        // dd($newControlNo);

        return $newControlNo;
    }

    // public function existsPminaaRequestRepository(array $conditions, ?string $excludePminaaId = null): bool{
    //     $query = PminaaDetails::where($conditions);

    //     if ($excludePminaaId) {
    //         $query->where('id', '!=', $excludePminaaId);
    //     }

    //     return $query->exists();
    // }

    private function mapPminaaData(array $data): array{
        $internetAccess = isset($data['get_internet_access']) ? json_decode($data['get_internet_access'], true) : null;
        $accountAccess = isset($data['get_account_system_access']) ? json_decode($data['get_account_system_access'], true) : null;
        $folderAccess = isset($data['get_folder_access']) ? json_decode($data['get_folder_access'], true) : null;

        return [
            'user_type'             => $data['user_type'] ?? null,
            'factory'               => $data['factory'] ?? null,
            // 'control_no'            => $data['control_no'] ?? null,
            'employee_no'           => $data['employee_no'] ?? null,
            'employee_lastname'     => $data['employee_lastname'] ?? null,
            'employee_name'         => $data['employee_name'] ?? null,
            'employee_middlename'   => $data['employee_middlename'] ?? null,
            'requested_by'          => $data['user_login'] ?? null,

            'nature_of_employment'  => $data['employment_type'] ?? null,
            'department_agency'     => $data['employee_department_agency'] ?? null,
            'position_job_title'    => $data['employee_position_job_title'] ?? null,
            'section'               => $data['employee_section'] ?? null,
            'division'              => $data['employee_division'] ?? null,
            'remarks'               => $data['remarks'] ?? null,

            'internet_access'       => $internetAccess ? json_encode($internetAccess, JSON_PRETTY_PRINT) : null,
            'account_system_access' => $accountAccess ? json_encode($accountAccess, JSON_PRETTY_PRINT) : null,
            'network_folder_access' => $folderAccess ? json_encode($folderAccess, JSON_PRETTY_PRINT) : null,
        ];
    }

    private function mapPminaaDataForEmail(array $data): array{
        $internetAccess = isset($data['get_internet_access']) ? json_decode($data['get_internet_access'], true) : null;
        $accountAccess = isset($data['get_account_system_access']) ? json_decode($data['get_account_system_access'], true) : null;
        $folderAccess = isset($data['get_folder_access']) ? json_decode($data['get_folder_access'], true) : null;

        if (isset($accountAccess['Details']) && is_array($accountAccess['Details'])) {
            usort($accountAccess['Details'], function ($a, $b) {
                return strcasecmp( $a['accountSystemAccess'] ?? '', $b['accountSystemAccess'] ?? ''
                );
            });
        }

        if (isset($folderAccess['Details']) && is_array($folderAccess['Details'])) {
            usort($folderAccess['Details'], function ($a, $b) {
                return strcasecmp( $a['folder_access'] ?? '', $b['folder_access'] ?? '' );
                });
        }

        return [
            'user_type'             => $data['user_type'] ?? null,
            'factory'               => $data['factory'] ?? null,
            'control_no'            => $data['control_no'] ?? null,
            'employee_no'           => $data['employee_no'] ?? null,
            'employee_lastname'     => $data['employee_lastname'] ?? null,
            'employee_name'         => $data['employee_name'] ?? null,
            'employee_middlename'   => $data['employee_middlename'] ?? null,
            'requested_by'          => $data['user_login'] ?? null,

            'nature_of_employment'  => $data['employment_type'] ?? null,
            'department_agency'     => $data['employee_department_agency'] ?? null,
            'position_job_title'    => $data['employee_position_job_title'] ?? null,
            'section'               => $data['employee_section'] ?? null,
            'division'              => $data['employee_division'] ?? null,
            'remarks'               => $data['remarks'] ?? null,

            'internet_access'       => $internetAccess ? json_encode($internetAccess, JSON_PRETTY_PRINT) : null,
            'account_system_access' => $accountAccess,
            'network_folder_access' => $folderAccess,
        ];
    }
    private function mapApproverData($data): array{
        return [
            'section_head'      => $data['section_head'] ?? null,
            'department_head'   => $data['department_head'] ?? null,
            'iss_manager'       => $data['iss_manager'] ?? null,
            'admin_avp'         => $data['admin_avp'] ?? null,
        ];
    }

    public function getPminaaRequestInfoByIdRepository($pminaaId){
        return PminaaDetails::with(['rapidx_user_info', 'approvers_info'])
            ->where('id', $pminaaId)
            ->where('logdel', 0)
            ->get();
    }

    public function pminaaRequestChangeApprovalStatusRepository(array $request, $rapidx_user_id){
        $pminaaId       = $request['pminaa_id'];
        $approvalStatus = $request['approval_status'];
        $remarkValue    = $request['approval_remark'] ?? null;

        $statusMap = [
            1    => 'section_head',
            2    => 'department_head',
            3    => 'iss_manager',
            4    => 'admin_avp',
            5    => 'iss_hardware',
            6    => 'section_head',
            7    => 'department_head',
            8    => 'iss_manager',
            9    => 'admin_avp',
            10   => 'iss_hardware',
        ];

        if (!isset($statusMap[$approvalStatus])) {
            throw new \Exception("Invalid approval status: $approvalStatus");
        }

        $currentStep = $statusMap[$approvalStatus];
        $approverInfo = PminaaApprover::where('pminaa_details_id', $pminaaId)
            ->first();

        if (!$approverInfo) {
            throw new \Exception("Approver info not found.");
        }

        $dateColumn   = $currentStep . '_approved_by_date';
        $remarkColumn = $currentStep . '_approved_by_remark';

        if($approvalStatus == 5 || $approvalStatus == 10){
            $getDataToCreateSystemModules = PminaaDetails::where('id', $pminaaId)->where('status', 0)->where('logdel', 0)->first();
            $systemModules = collect(json_decode($getDataToCreateSystemModules->account_system_access, true)['Details'])
                ->whereIn('accountSystemAccess', ['Rapid', 'RapidX', 'SystemOne'])
                ->groupBy('accountSystemAccess')
                ->toArray();

            $employee_details = [
                'user_type'             => $getDataToCreateSystemModules->user_type,
                'employee_no'           => $getDataToCreateSystemModules->employee_no,
                'employee_lastname'     => $getDataToCreateSystemModules->employee_lastname,
                'employee_name'         => $getDataToCreateSystemModules->employee_name,
                'employee_middlename'   => $getDataToCreateSystemModules->employee_middlename,
                'department_agency'     => $getDataToCreateSystemModules->department_agency,
                'position_job_title'    => $getDataToCreateSystemModules->position_job_title,
                'section'               => $getDataToCreateSystemModules->section,
                'division'              => $getDataToCreateSystemModules->division,
                'nature_of_employment'  => $getDataToCreateSystemModules->nature_of_employment,
            ];

            // dd($employee_name);

            if($approvalStatus == 5){
                $this->mapCreationOfSystemModule($systemModules, $employee_details, $rapidx_user_id);
            }

            PminaaApprover::where('pminaa_details_id', $pminaaId)
                ->update([
                    'iss_hardware' => $rapidx_user_id,
                    $dateColumn   => now()->format('Y-m-d H:i:s'),
                    $remarkColumn => $remarkValue,
                    'updated_at'  => now()
                ]); // CHAN
        }else{
            PminaaApprover::where('pminaa_details_id', $pminaaId)
                ->update([
                    $dateColumn   => now()->format('Y-m-d H:i:s'),
                    $remarkColumn => $remarkValue,
                    'updated_at'  => now()
                ]); // CHAN
        }

        PminaaDetails::where('id', $pminaaId)->update([
            'approval_status' => $approvalStatus,
            'updated_at'      => now(),
        ]); // CHAN

        $nextStatus = $approvalStatus + 1;
        $step = $statusMap[$nextStatus] ?? null;

        $pminaaData =
            PminaaDetails::
                with(['rapidx_user_info', 'approvers_info.section_head_info',
                    'approvers_info.department_head_info',
                    'approvers_info.iss_manager_info',
                    'approvers_info.admin_avp_info',
                    'approvers_info.iss_hardware_info'])
                ->where('id', $pminaaId)
                ->where('logdel', 0)
                ->get();
        // dd($pminaaData[0], $pminaaData[0]['rapidx_user_info']['email'], $pminaaData[0]['approvers_info'], $step);

        $pminaa = PminaaDetails::with([
            'rapidx_user_info',
            'approvers_info.section_head_info',
            'approvers_info.department_head_info',
            'approvers_info.iss_manager_info',
            'approvers_info.admin_avp_info',
            'approvers_info.iss_hardware_info'
        ])
        ->where('id', $pminaaId)
        ->where('logdel', 0)
        ->first();

        $email = $pminaa->requested_by;

        $requestedBy = ['email' => $pminaa->rapidx_user_info->email ?? null];

        $accountAccess = isset($pminaa['account_system_access']) ? json_decode($pminaa['account_system_access'], true) : null;
        $folderAccess = isset($pminaa['network_folder_access']) ? json_decode($pminaa['network_folder_access'], true) : null;

        if (isset($accountAccess['Details']) && is_array($accountAccess['Details'])) {
            usort($accountAccess['Details'], function ($a, $b) {
                return strcasecmp( $a['accountSystemAccess'] ?? '', $b['accountSystemAccess'] ?? ''
                );
            });
        }

        if (isset($folderAccess['Details']) && is_array($folderAccess['Details'])) {
            usort($folderAccess['Details'], function ($a, $b) {
                return strcasecmp( $a['folder_access'] ?? '', $b['folder_access'] ?? '' );
            });
        }

        $pminaaData = [
            'user_type'             => $pminaa->user_type ?? null,
            'employee_no'           => $pminaa->employee_no ?? null,
            'control_no'            => $pminaa->control_no ?? null,
            'employee_name'         => $pminaa->employee_name ?? null,
            'employee_lastname'     => $pminaa->employee_lastname ?? null,
            'employee_middlename'   => $pminaa->employee_middlename ?? null,
            'nature_of_employment'  => $pminaa->nature_of_employment ?? null,
            'department_agency'     => $pminaa->department_agency ?? null,
            'position_job_title'    => $pminaa->position_job_title ?? null,
            'remarks'               => $pminaa->remarks ?? null,
            'internet_access'       => $pminaa->internet_access ?? null,
            'account_system_access' => $accountAccess ?? null,
            'network_folder_access' => $folderAccess ?? null,
        ];

        $test = $this->getConformanceApprovalRepository();
        // dd($test->pluck('rapidx_user_id')->toArray());
        $approverData = [
            'section_head'      => $pminaa->approvers_info[0]->section_head_info->id ?? null,
            'department_head'   => $pminaa->approvers_info[0]->department_head_info->id ?? null,
            'iss_manager'       => $pminaa->approvers_info[0]->iss_manager_info->id ?? null,
            'admin_avp'         => $pminaa->approvers_info[0]->admin_avp_info->id ?? null,
            'iss_hardware'      => $test->pluck('rapidx_user_id')->toArray(),
            'approved'          => 'ISS Hardware',
        ];

        // dd(
        //     $pminaaData,
        //     $requestedBy,
        //     $email,
        //     $approvalStatus,
        //     $approverData
        // );

        $this->mapEmailNotification($pminaaData, $requestedBy, $approvalStatus, $approverData, $rapidx_user_id, $remarkValue); // CHAN
        return true;
    }

    private function mapEmailNotification(array $pminaaData, $requestedBy, $approvalStatus, $approverData, $rapidx_user_id, $remarkValue): bool{
        $pminaa = $pminaaData;
        $step = (int)$approvalStatus;
        $pminaaDetails = ['data' => $pminaa];
        $pminaaDetails['approval_status'] = $step;
        // dd($pminaaDetails['approval_status']);
        $pminaaRequestedBy = $requestedBy[0]['email'] ?? $requestedBy['email'] ?? '';

        $stepMap = [
            0   => 'section_head',
            1   => 'department_head',
            2   => 'iss_manager',
            3   => 'admin_avp',
            4   => 'iss_hardware',
            5   => 'approved',
            6   => 'section_head',
            7   => 'department_head',
            8   => 'iss_manager',
            9   => 'admin_avp',
            10  => 'iss_hardware',
        ];

        if (!isset($stepMap[$step])) {
            return false;
        }

        $stepKey = $stepMap[$step];

        if($step == 5 || $step == 10){
            $approverId = $rapidx_user_id ?? null;
        }else{
            $approverId = $approverData[$stepKey] ?? null;
        }
        // dd($approverId);

        $user = RapidxUser::find($approverId);

        if($step == 4){
            $email_to = $user->pluck('email')
                    ->filter()
                    ->toArray();
        }else if($step == 5){
            $email_to = ['group-hw@pricon.ph','group-iss-software@pricon.ph'];
        }else{
            $email_to = $user->email ?? '';
        }

        // dd($pminaaRequestedBy);
        if ($user && !empty($email_to)) {
            $username = '';
            if ($pminaa) {
                $username .= Str::lower(Str::substr($pminaa['employee_name'] ?? '', 0, 1));
            }

            if ($pminaa && !preg_match('/^[-.]+$/', trim($pminaa['employee_middlename'] ?? ''))) {
                $username .= Str::lower(Str::substr(trim($pminaa['employee_middlename'] ?? ''), 0, 1));
            }

            if ($pminaa) {
                $surname = Str::ascii($pminaa['employee_lastname'] ?? ''); // ñ → n
                $surname = preg_replace('/[\s-]+/', '', $surname);
                $username .= Str::lower($surname);
            }
            $pminaaDetails['username']  = $username;
            $pminaaDetails['remark']    = $remarkValue;

            try {
                Mail::send('mail.pminaa_mail', $pminaaDetails, function ($message) use ($pminaaRequestedBy, $email_to) {
                    $message->to($email_to)
                            ->cc($pminaaRequestedBy)
                            ->bcc('cbretusto@pricon.ph')
                            ->subject('PMINAA Approval Notification');
                });
                \Log::info('PMINAA email sent successfully to: ' . $email_to);
            } catch (\Exception $e) {
                \Log::error('PMINAA email failed: ' . $e->getMessage());
            }

            // dd($pminaaDetails);

            // Mail::send('mail.pminaa_mail', $pminaaDetails, function ($message) use ($pminaaRequestedBy, $email_to) {
            //     $message->to($email_to)
            //             ->cc($pminaaRequestedBy)
            //             ->bcc('cbretusto@pricon.ph')
            //             ->subject('PMINAA Approval Notification');
            // });
        }

        return true;
    }

    public function getConformanceApprovalRepository(){
        return UserManagement::whereJsonContains('classification', '5')
            ->select('rapidx_user_id')
            ->where('status', 0)
            ->where('logdel', 0)
            ->get();
    }

    private function mapCreationOfSystemModule(array $systemModules, $employee_details, $rapidx_user_id): array{
        $result         = [];
        $userType       = $employee_details['user_type'] ?? null;
        $userEmployeeNo = $employee_details['employee_no'] ?? null;
        $userFirstName  = $employee_details['employee_name'] ?? null;
        $userMiddleName = $employee_details['employee_middlename'] ?? null;
        $userLastName   = $employee_details['employee_lastname'] ?? null;
        $userPosition   = $employee_details['position_job_title'] ?? null;
        $userSection    = $employee_details['section'] ?? null;
        $userDivision   = $employee_details['division'] ?? null;
        $userDepartment = $employee_details['department_agency'] ?? null;
        $userNatureOfEmployment = $employee_details['nature_of_employment'] ?? null;
        // dd($userSection);

        $username = '';
        if ($userFirstName) {
            $username .= Str::lower(Str::substr($userFirstName, 0, 1));
        }

        if ($userMiddleName &&!preg_match('/^[-.]+$/', trim($userMiddleName))) {
            $username .= Str::lower(Str::substr(trim($userMiddleName), 0, 1));
        }

        if ($userLastName) {
            $surname = Str::ascii($userLastName); // ñ → n
            $surname = preg_replace('/[\s-]+/', '', $surname);
            $username .= Str::lower($surname);
        }
        // dd($employee_details);

        foreach ($systemModules as $system => $modules) {
            if ($system === 'Rapid') {
                $rapidUser = RapidUser::with('rapid_assign_module_details')
                    ->where('username', $username)
                    ->where('logdel', 0)
                    ->first();
                if (!$rapidUser) {
                    $rapidUser = RapidUser::insertGetId([
                        'empno'             => $userEmployeeNo,
                        'username'          => $username,
                        'password'          => 'mlYeOVfHJBl4o',
                        'name'              => $userFirstName . ' ' . $userMiddleName . ' ' . $userLastName,
                        'position'          => $userPosition,
                        'section'           => $userSection,
                        'department'        => $userDepartment,
                        'division'          => $userDivision,
                        'email_add'         => $username . '@pricon.ph',
                        'accountType'       => 1,
                        'hashed_id_ip'      => '',
                        'emp_type'          => $userType == 'PMI' ? 'Pricon Employee' : 'Subcon Hired',
                        'lastLogin'         => '',
                        'loginIPAddress'    => '',
                        'un'                => '',
                        'lastupdate'        => now(),
                        'isEnable'          => 0,
                        'logdel'            => 0,
                    ]);
                    $getRapidUserId = $rapidUser;
                }else{
                    $getRapidUserId = $rapidUser->id;
                    // dd(RapidUser::where('id', $getRapidUserId)->get());
                    RapidUser::where('id', $getRapidUserId)
                        ->update([
                            'password'          => 'mlYeOVfHJBl4o',
                            'position'          => $userPosition,
                            'section'           => $userSection,
                            'department'        => $userDepartment,
                            'division'          => $userDivision,
                            'email_add'         => $username . '@pricon.ph',
                            'emp_type'          => $userType == 'PMI' ? 'Pricon Employee' : 'Subcon Hired',
                        ]);
                }

                foreach ($modules as $module) {
                    $rapidModule = RapidModule::where('moduleName',$module['accountSystemName'])->where('logdel', 0)->first();
                    // dd($rapidModule);

                    if (!$rapidModule) {
                        continue;
                    }

                    $rapidAssignModule = RapidAssignModule::where('username',$getRapidUserId)->where('module',$rapidModule->pkid)->first();

                    if (!$rapidAssignModule) {
                        RapidAssignModule::insert([
                            'username'      => $getRapidUserId,
                            'module'        => $rapidModule->pkid,
                            'logdel'        => 0,
                            'updated_at'    => now(),
                        ]);
                    }

                    $result[] = $module;
                }
            }

            if ($system === 'RapidX') {
                $password = 'pmi1234';
                foreach ($modules as $module) {
                    $rapidxUser = RapidxUser::with('rapidx_assign_module_details')
                        ->where('username', $username)
                        ->where('user_stat', '!=', 2)
                        ->first();

                    if (!$rapidxUser) {
                        $rapidxUser = RapidxUser::insertGetId([
                            'employee_number'       => $userEmployeeNo,
                            'username'              => $username,
                            'name'                  => $userFirstName . ' ' . $userMiddleName . ' ' . $userLastName,
                            'email'                 => $username . '@pricon.ph',
                            'password'              => Hash::make($password),
                            'is_password_changed'   => 0,
                            'user_stat'             => 1,
                            'user_level_id'         => '3',
                            'department_id'         => '75',
                            'update_version'        => 1,
                            'created_at'            => date('Y-m-d H:i:s')
                        ]);
                        $getRapidxUserId = $rapidxUser;
                    }else{
                        $getRapidxUserId = $rapidxUser->id;
                        RapidxUser::where('id', $getRapidxUserId)
                        ->update([
                            'password'              => Hash::make($password),
                            'employee_number'       => $userEmployeeNo,
                            'email'                 => $username . '@pricon.ph',
                        ]);
                    }

                    $modules = array_merge($modules, [
                        ['accountSystemName' => 'ISS Service Request System'],
                        ['accountSystemName' => 'TRDSv2'],
                        ['accountSystemName' => 'PMI Network Account Activation (PMINAA) v2'],
                    ]);

                    // if($userType === 'PMI'){
                    //     $modules = array_merge($modules, [
                    //         ['accountSystemName' => 'PMI Network Account Activation (PMINAA) v2'],
                    //     ]);
                    // }
                    foreach ($modules as $module) {
                        $rapidxModule = RapidxModule::where('module_name',$module['accountSystemName'])->where('module_stat', 1)->first();
                        // dd($rapidxModule->module_id);

                        if (!$rapidxModule) {
                            continue;
                        }

                        $rapidxAssignModule = RapidxAssignModule::where('user_id',$getRapidxUserId)->where('module_id',$rapidxModule->module_id)->first();

                        if (!$rapidxAssignModule) {
                            RapidxAssignModule::insert([
                                'user_id'           => $getRapidxUserId,
                                'module_id'         => $rapidxModule->module_id,
                                'created_by'        => $rapidx_user_id,
                                'last_updated_by'   => $rapidx_user_id,
                                'user_level_id'     => '5',
                                'user_access_stat'  => '1',
                                'update_version'    => '1',
                            ]);
                        }

                        $result[] = $module;
                    }
                }
            }

            if($userType === 'PMI'){
                if ($system === 'SystemOne') {
                    $systemOneUser = SystemOneHrisEmployeeInfo::with('acl_user_info.systemone_assign_module_details')
                        ->where('EmpNo', $userEmployeeNo)
                        ->where('EmpStatus', 1)
                        ->first();
                    // dd($systemOneUser['acl_user_info']['systemone_assign_module_details']);

                    if (!$systemOneUser['acl_user_info']) {
                        $systemOneUser = SystemOneAclUser::insertGetId([
                            'username'          => $username,
                            'password'          => '67a311d0a7439f5028561501ddb57230',
                            'email_add'         => $username . '@pricon.ph',
                            'FN'                => $userFirstName,
                            'LN'                => $userLastName,
                            'crdate'            => date('Y-m-d H:i:s'),
                            'fksec'             => $systemOneUser->fkSection,
                            'type'              => '1',
                            'fkemployee'        => $systemOneUser->pkid,
                            'isEnable'          => '0',
                            'loginIPAddress'    => '',
                            'un'    => '',
                            'pass_lastupdate'   => now(),
                            'lastupdate'   => now(),
                        ]);
                        $SystemOneUserId = $systemOneUser;
                    }else{
                        $SystemOneUserId = $systemOneUser['acl_user_info']['pkid'];
                        SystemOneAclUser::where('pkid', $SystemOneUserId)
                            ->update([
                            'password'          => '67a311d0a7439f5028561501ddb57230',
                        ]);
                    }

                    foreach ($modules as $module) {
                        $systemOneModule = SystemOneAclModule::where('modname',$module['accountSystemName'])->where('logdel', 0)->first();
                        // dd($systemOneModule);

                        if (!$systemOneModule) {
                            continue;
                        }

                        $systemOneAssignModule = SystemOneAclAssignModule::where('fkuser',$SystemOneUserId)->where('fkmodname',$systemOneModule->pkid)->first();
                        if (!$systemOneAssignModule) {
                            SystemOneAclAssignModule::insert([
                                'fkuser'        => $SystemOneUserId,
                                'fkmodname'     => $systemOneModule->pkid,
                                'fksystem'      => $systemOneModule->fksystem,
                                'crdate'        => date('Y-m-d H:i:s'),
                                'lastupdate'    => date('Y-m-d H:i:s'),
                                'username'      => '',
                                'logdel'        => 0,
                            ]);
                        }

                        $result[] = $module;
                    }
                }
            }
        }

        return $result;
    }

    public function viewPdfPminaaRequestRepository($id){
        $query = PminaaDetails::where('id', $id)->firstOrFail();

        $data = $query->toArray();

        $data['internet_access'] = json_decode(
            $data['internet_access'],
            true
        );

        $data['account_system_access'] = json_decode(
            $data['account_system_access'],
            true
        );

        $data['network_folder_access'] = json_decode(
            $data['network_folder_access'],
            true
        );

        // ASC by accountSystemAccess
        if (!empty($data['account_system_access']['Details'])) {
            usort($data['account_system_access']['Details'], function ($a, $b) {
                return strcasecmp(
                    $a['accountSystemAccess'] ?? '',
                    $b['accountSystemAccess'] ?? ''
                );
            });
        }

        $pdf = PDF::loadView('view_pdf_pminaa_request', [
            'data' => $data,
        ]);

        $pdf->setPaper('A4', 'Portrait');

        return $pdf->stream();
    }

    public function approveAllPendingRequestsRepository($presidentApproval){
        $approvers = PminaaApprover::with('pminaa_info')
            ->where(function ($query) use ($presidentApproval) {
                $query->where(function ($q) use ($presidentApproval) {
                    $q->where('section_head', $presidentApproval)
                    ->whereHas('pminaa_info', function ($q) {
                        $q->where('approval_status', 0);
                        $q->where('status', 0);
                        $q->where('logdel', 0);
                    });
                })
                ->orWhere(function ($q) use ($presidentApproval) {
                    $q->where('department_head', $presidentApproval)
                    ->whereHas('pminaa_info', function ($q) {
                        $q->where('approval_status', 1);
                        $q->where('status', 0);
                        $q->where('logdel', 0);
                    });
                });
            })
            ->get();

        // dd($approvers);
        if ($approvers->isEmpty()) {
            return [
                'hasPendingRequests' => 0,
            ];
        }

        foreach ($approvers as $approver) {
            $pminaaInfo = $approver->pminaa_info;
            $currentApprovalStatus = $pminaaInfo->approval_status;

            $pminaaInfo->update([
                'approval_status' => $currentApprovalStatus + 1,
            ]);

            if (
                $approver->section_head == $presidentApproval &&
                $currentApprovalStatus == 0
            ) {
                $approver->update([
                    'section_head_approved_by_date' => now(),
                ]);
            }

            if (
                $approver->department_head == $presidentApproval &&
                $currentApprovalStatus == 1
            ) {
                $approver->update([
                    'department_head_approved_by_date' => now(),
                ]);
            }
        }

        return [
            'hasPendingRequests' => 1,
        ];
    }

}

