<?php

namespace App\Services;

// use Illuminate\Support\Collection;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use App\Interfaces\PminaaRequestInterface;

class PminaaRequestService
{
    protected $pminaaRequestInterfaceInterface;

    public function __construct(
        PminaaRequestInterface $pminaaRequestInterfaceInterface
    ){
        $this->pminaaRequestInterfaceInterface = $pminaaRequestInterfaceInterface;
    }

    public function getPminaaRequestForDataTableService($request){
        session_start();

        $rapidx_user_id = $_SESSION['rapidx_user_id'];
        $rapidx_department_id = $_SESSION['rapidx_department_id'];
        $pminaa_access_for_conformance = $_SESSION['pminaa_request_access_for_conformance'];

        $steps = [
            'section_head'      => 'Checked By',
            'department_head'   => 'Checked By',
            'iss_manager'       => 'Noted By',
            'admin_avp'         => 'Approved By',
            'iss_hardware'      => 'Conformed By',
        ];

        $approve_values = [
            'section_head'      => 0,
            'department_head'   => 1,
            'iss_manager'       => 2,
            'admin_avp'         => 3,
            'iss_hardware'      => 4,
        ];

        $disapprove_values = [
            'section_head'      => 6,
            'department_head'   => 7,
            'iss_manager'       => 8,
            'admin_avp'         => 9,
            'iss_hardware'      => 10,
        ];

        $conformance_approval   = $this->pminaaRequestInterfaceInterface->getConformanceApprovalRepository();
        $conformanceApprovalIds = $conformance_approval->pluck('rapidx_user_id')->toArray();
        $pminaa_details         = $this->pminaaRequestInterfaceInterface->getAllPminaaRequestDataRepository($request,$rapidx_user_id,$rapidx_department_id,$conformanceApprovalIds);
        $employeeNumbers = $pminaa_details
            ->pluck('employee_no')
            ->filter()
            ->unique()
            ->values();

        $sameEmployeeRequests = $this->pminaaRequestInterfaceInterface
            ->findSameEmployeeRequestsRepository($employeeNumbers);

        $pminaa_details = $pminaa_details->sort(function ($a, $b) use ($rapidx_user_id, $conformanceApprovalIds){
            $getApprovalPriority = function ($detail) use ( $rapidx_user_id, $conformanceApprovalIds ){
                $approvalStatus = (int) $detail->approval_status;
                $approvers = $detail->approvers_info->first();

                if(!$approvers){
                    return 1;
                }

                if($approvalStatus === 0 && (int) $approvers->section_head === (int) $rapidx_user_id){
                    return 0;
                }

                if($approvalStatus === 1 && (int) $approvers->department_head === (int) $rapidx_user_id){
                    return 0;
                }

                if($approvalStatus === 2 && (int) $approvers->iss_manager === (int) $rapidx_user_id){
                    return 0;
                }

                if($approvalStatus === 3 && (int) $approvers->admin_avp === (int) $rapidx_user_id){
                    return 0;
                }

                if($approvalStatus === 4 &&
                    in_array(
                        (int) $rapidx_user_id,
                        array_map('intval', $conformanceApprovalIds),
                        true
                    )
                ){
                    return 0;
                }

                return 1;
            };

            $priorityA = $getApprovalPriority($a);
            $priorityB = $getApprovalPriority($b);

            if($priorityA !== $priorityB){
                return $priorityA <=> $priorityB;
            }

            $statusA = (int) $a->approval_status;
            $statusB = (int) $b->approval_status;

            if($statusA !== $statusB){
                return $statusB <=> $statusA;
            }

            $controlNoA = (int) $a->control_no;
            $controlNoB = (int) $b->control_no;

            return $controlNoB <=> $controlNoA;
        })
        ->values();

        return DataTables::of($pminaa_details)
            ->addColumn('action',function ($pminaa_detail) use ( $rapidx_user_id, $steps, $approve_values, $disapprove_values, $pminaa_access_for_conformance, $conformanceApprovalIds, $sameEmployeeRequests ){
                    $btns = '<center>';
                    $btns .= '
                        <a
                            href="view_pdf_pminaa_request/' . $pminaa_detail->id . '"
                            target="_blank"
                            class="btn btn-warning btn-sm mb-2 w-100"
                            title="View PMINAA Request">
                            <i class="fa fa-eye"></i>
                        </a>
                    ';

                    if ($rapidx_user_id == $pminaa_detail->requested_by && $pminaa_detail->approval_status != 5){
                        if ($pminaa_detail->status == 0) {
                            $btns .= '
                                <button
                                    type="button"
                                    class="btn btn-dark btn-sm actionUpdatePminaaRequest w-100 mb-3"
                                    pminaa_details-id="' . $pminaa_detail->id . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCreateUpdatePminaaRequest"
                                    title="Update PMINAA Request"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                            ';
                        }
                    }

                    $currentStepKey = null;
                    $approveVal = null;
                    $disapproveVal = null;

                    foreach($approve_values as $key => $val){
                        if ($pminaa_detail->approval_status == $val) {
                            $currentStepKey = $key;
                            $approveVal = $val + 1;
                            $disapproveVal = $disapprove_values[$key];
                            break;
                        }
                    }

                    if($currentStepKey && $pminaa_detail->status == 0){
                        $btns .= '<br>';
                        $currentApproverId = null;
                        if(!$pminaa_detail->approvers_info->isEmpty()){
                            $approver = $pminaa_detail->approvers_info->first();
                            $currentApproverId = $approver->{$currentStepKey} ?? null;
                        }

                        $canApprove = ($currentApproverId == $rapidx_user_id &&$pminaa_detail->approval_status <= 3);
                        $canConform =
                            (
                                ($pminaa_detail->pc_account != '' || $pminaa_detail->email_account != '') &&
                                $pminaa_detail->approval_status == 4 &&
                                in_array(
                                    (int) $rapidx_user_id,
                                    array_map('intval', $conformanceApprovalIds),
                                    true
                                ) &&
                                $pminaa_access_for_conformance == 1
                            );

                        if($canApprove || $canConform){
                            $btns .= '
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm actionPminaaRequestApprovalButton w-100 mb-2"
                                    pminaa_details-id="' . $pminaa_detail->id . '"
                                    pminaa_details-approval_status="' . $currentStepKey . '"
                                    approval-status="' . $approveVal . '"
                                    value="approve"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPminaaRequestApproval"
                                    title="Approve">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                            ';

                            $btns .= '
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm actionPminaaRequestApprovalButton w-100"
                                    pminaa_details-id="' . $pminaa_detail->id . '"
                                    pminaa_details-approval_status="' . $currentStepKey . '"
                                    approval-status="' . $disapproveVal . '"
                                    value="disapprove"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPminaaRequestApproval"
                                    title="Disapprove">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </button>
                            ';
                        }else{
                            $btns .= '
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm w-100 actionPminaaRequestUserAccountButton"
                                    pminaa_details-id="' . $pminaa_detail->id . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPminaaRequestUserAccount"
                                    title="User Account">
                                    <i class="fa-solid fa-user"></i>
                                </button>
                            ';
                        }
                    }

                    //====================================================================================
                    //====================================================================================
                    //====================================================================================
                    $employeeRequests = $sameEmployeeRequests->get(
                        $pminaa_detail->employee_no,
                        collect()
                    );
                    $employeeRequestCount = $employeeRequests->count();
                    $employeeRequestIds = $employeeRequests
                        ->pluck('id')
                        ->implode(',');

                    if($employeeRequestCount > 1){
                        $btns .= '<br>';
                        $btns .= '
                            <a
                                href="view_pdf_pminaa_request/' . $employeeRequestIds . '"
                                target="_blank"
                                class="btn btn-dark btn-sm mb-2 w-100"
                                title="View PMINAA Request History">
                                <i class="fa fa-file-pdf"></i>
                            </a>
                        ';
                    }

                    $btns .= '</center>';
                    return $btns;
                }
            )

            ->addColumn('created_at', function ($pminaa_detail) {
                return $pminaa_detail->created_at
                    ? $pminaa_detail->created_at->format('F d, Y')
                    : '';
            })

            ->addColumn('full_name', function ($pminaa_detail) {
                return trim(
                    ($pminaa_detail->employee_name ?? '') . ' ' .
                    ($pminaa_detail->employee_lastname ?? '')
                );
            })

            ->addColumn('factory', function ($pminaa_detail){
                return $pminaa_detail->factory
                    ? 'Factory ' . $pminaa_detail->factory
                    : '';
            })

            ->addColumn('requested_by', function ($pminaa_detail){
                return $pminaa_detail->rapidx_user_info->name ?? '';
            })

            ->addColumn('approvers',function ($pminaa_detail) use ($steps, $approve_values, $disapprove_values){
                $step_keys = array_keys($steps);
                $approval_status = (int) $pminaa_detail->approval_status;
                $approvers = $pminaa_detail->approvers_info;

                if($approvers->isEmpty()){
                    return '<center>No Approvers</center>';
                }

                $approvers = $approvers->first();
                $currentStepKey = null;

                foreach($approve_values as $key => $val) {
                    if($approval_status <= $val){
                        $currentStepKey = $key;
                        break;
                    }
                }

                $result = '<center>';

                foreach($step_keys as $key){
                    $obj            = $approvers->{$key . '_info'} ?? null;
                    $name           = $obj ? $obj->name : 'ISS Hardware';
                    $date           = $approvers->{$key . '_approved_by_date'} ?? null;
                    $remark         = $approvers->{$key . '_approved_by_remark'} ?? null;
                    $personBadge    = 'bg-light text-black';

                    if($approval_status == $disapprove_values[$key]){
                        $personBadge = 'bg-danger text-white';
                    }elseif( $approval_status > $approve_values[$key] && $approval_status < 6 ){
                        $personBadge = 'bg-success text-white';
                    }elseif ($key === $currentStepKey) {
                        $personBadge = 'bg-warning text-black';
                    }

                    $result .= '<span class="badge ' . $personBadge .'">' . $name . '</span><br>';

                    if(!empty($date)){
                        $result .= ' <small>' . $date . '</small> <br>';
                    }

                    if(!empty($remark)){
                        $result .= ' <strong>Remark:</strong> ' . $remark . '<br>';
                    }
                }
                $result .= '</center>';
                return $result;
            })

            ->addColumn('activation_date', function ($pminaa_detail) {
                $date = $pminaa_detail->approvers_info[0]->iss_hardware_approved_by_date ?? null;

                return $date ? Carbon::parse($date)->format('F Y') : '';
            })
            ->rawColumns([
                'action',
                'full_name',
                'approvers',
                'activation_date'
            ])
            ->make(true);
    }

    public function getSystemonePmiSubconEmployeeService($user_type){
        return $this->pminaaRequestInterfaceInterface->getSystemonePmiSubconEmployeeRepository($user_type);
    }

    public function getEmployeeInfoService($employee_no, $user_type){
        return $this->pminaaRequestInterfaceInterface->getEmployeeInfoRepository($employee_no, $user_type);
    }

    public function getPminaaApproverService(){
        return $this->pminaaRequestInterfaceInterface->getPminaaApproverRepository();
    }

    public function getAccountSystemFolderAccessService(){
        return $this->pminaaRequestInterfaceInterface->getAccountSystemFolderAccessRepository();
    }

    public function getAccountSystemFolderNameService($get_access_id, $get_system_module){
        return $this->pminaaRequestInterfaceInterface->getAccountSystemFolderNameRepository($get_access_id, $get_system_module);
    }

    public function createUpdatePminaaRequestService( ?string $pminaaId, array $data ): array {
        session_start();

        $rapidx_user_id = $_SESSION['rapidx_user_id'];

        return DB::transaction(function () use ( $pminaaId, $data, $rapidx_user_id) {
            $result = $this->pminaaRequestInterfaceInterface
                ->createUpdatePminaaRequestRepository(
                    $pminaaId,
                    $data,
                    $rapidx_user_id
                );

            if ($result === false) {
                return [
                    'hasError' => 1,
                    'message' => 'Saving failed because control number is empty.'
                ];
            }

            return [
                'hasError' => 0,
                'message' => 'Successfully saved.'
            ];
        }, 5);
    }

    public function pminaaRequestUserAccountService( ?string $pminaaId, array $data ): array {
        session_start();
        $rapidx_user_id = $_SESSION['rapidx_user_id'];

        return DB::transaction(function () use ( $pminaaId, $data, $rapidx_user_id) {
            $result = $this->pminaaRequestInterfaceInterface
                ->pminaaRequestUserAccountRepository(
                    $pminaaId,
                    $data,
                    $rapidx_user_id
                );

            if ($result === false) {
                return [
                    'hasError' => 1,
                    'message' => 'Saving failed because control number is empty.'
                ];
            }

            return [
                'hasError' => 0,
                'message' => 'Successfully saved.'
            ];
        }, 5);
    }


    public function getPminaaRequestInfoByIdService($pminaaId){
        return $this->pminaaRequestInterfaceInterface->getPminaaRequestInfoByIdRepository($pminaaId);
    }

    public function pminaaRequestChangeApprovalStatusService(array $data): array{
        session_start();
        $rapidx_user_id = $_SESSION['rapidx_user_id'];

        return DB::transaction(function () use ($data, $rapidx_user_id){
            $this->pminaaRequestInterfaceInterface->pminaaRequestChangeApprovalStatusRepository($data, $rapidx_user_id);
            return ['hasError' => 0];
        }, 5);
    }

    public function viewPdfPminaaRequestService($ids){
        // return $this->pminaaRequestInterfaceInterface->viewPdfPminaaRequestRepository($id);

        $data = $this->pminaaRequestInterfaceInterface
        ->viewPdfPminaaRequestRepository($ids);

        $pdf = PDF::loadView('view_pdf_pminaa_request', [
            'data' => $data,
        ]);

        $pdf->setPaper('A4', 'Portrait');

        return $pdf->stream();
    }

    public function approveAllPendingRequestsService($presidentApproval){
        return DB::transaction(function () use ($presidentApproval) {

            $result = $this->pminaaRequestInterfaceInterface
                ->approveAllPendingRequestsRepository($presidentApproval);

            return [
                'hasError' => 0,
                'hasPendingRequests' => $result['hasPendingRequests'] ?? 0,
            ];

        }, 5);
    }
}
