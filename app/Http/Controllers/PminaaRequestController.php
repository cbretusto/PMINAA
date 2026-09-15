<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PminaaRequestRequest;
use App\Services\PminaaRequestService;
use Illuminate\Http\Request;

class PminaaRequestController extends Controller
{
    protected $pminaaRequestServiceService;

    public function __construct(
        PminaaRequestService $pminaaRequestServiceService
    ){
        $this->pminaaRequestServiceService = $pminaaRequestServiceService;
    }

    public function viewPminaaRequest(Request $request){
        return $this->pminaaRequestServiceService->getPminaaRequestForDataTableService($request);
    }

    public function getSystemonePmiSubconEmployee(Request $request){
        $user_type = $request->userType;
        $systemone_pmi_subcon_employee = $this->pminaaRequestServiceService->getSystemonePmiSubconEmployeeService($user_type);
        return response()->json(['systemonePmiSubconEmployee' => $systemone_pmi_subcon_employee]);
    }

    public function getEmployeeInfo(Request $request){
        $employee_no = $request->employeeNo;
        $user_type = $request->userType;
        $get_employee_info = $this->pminaaRequestServiceService->getEmployeeInfoService($employee_no, $user_type);
        return response()->json(['getEmployeeInfo' => $get_employee_info]);
    }

    public function getPminaaApprover(){
        $pminaa_approver = $this->pminaaRequestServiceService->getPminaaApproverService();
        return response()->json(['pminaaApprover' => $pminaa_approver]);
    }

    public function getAccountSystemFolderAccess(){
        $account_system_folder_access = $this->pminaaRequestServiceService->getAccountSystemFolderAccessService();
        return response()->json(['accountSystemFolderAccess' => $account_system_folder_access]);
    }

    public function getAccountSystemFolderName(Request $request){
        $get_access_id = $request->getAccess;
        $get_system_module = $request->getSystemModule;
        $account_system_folder_name = $this->pminaaRequestServiceService->getAccountSystemFolderNameService($get_access_id, $get_system_module);
        return response()->json(['accountSystemFolderName' => $account_system_folder_name]);
    }

    public function createUpdatePminaaRequest(PminaaRequestRequest $request){
        $pminaaId = $request->pminaa_id;
        $data = $request->all();
        $result = $this->pminaaRequestServiceService->createUpdatePminaaRequestService($pminaaId, $data);

        return response()->json($result);
    }

    public function pminaaRequestUserAccount(PminaaRequestRequest $request){
        $pminaaId = $request->get_pminaa_id;
        $data = $request->all();
        $result = $this->pminaaRequestServiceService->pminaaRequestUserAccountService($pminaaId, $data);

        return response()->json($result);
    }

    public function getPminaaRequestInfoById(Request $request){
        $pminaaId = $request->pminaaId;
        $pminaaRequestInfo = $this->pminaaRequestServiceService->getPminaaRequestInfoByIdService($pminaaId);
        return response()->json(['pminaaRequestInfo' => $pminaaRequestInfo]);
    }

    public function pminaaRequestChangeApprovalStatus(Request $request){
        $result = $this->pminaaRequestServiceService->pminaaRequestChangeApprovalStatusService($request->all());
        if ($result['hasError'] === 0) {
            return response()->json(['hasError' => 0]);
        }else{
            return response()->json([
                'hasError' => 1,
                'exceptionError' => $result['exceptionError'] ?? 'An unknown error occurred.',
            ], 500);
        }
    }

    public function viewPdfPminaaRequest($ids){
        return $this->pminaaRequestServiceService->viewPdfPminaaRequestService($ids);
    }

    public function approveAllPendingRequests(Request $request){
        $presidentApproval = $request->presidentApproval;
        $result = $this->pminaaRequestServiceService->approveAllPendingRequestsService($presidentApproval);

        if ($result['hasError'] === 0) {
            return response()->json([
                'hasError' => 0,
                'hasPendingRequests' => $result['hasPendingRequests'] ?? 0,
            ]);
        }

        return response()->json([
            'hasError' => 1,
            'exceptionError' => $result['exceptionError'] ?? 'An unknown error occurred.',
        ], 500);
    }
}
