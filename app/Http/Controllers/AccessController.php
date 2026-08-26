<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AccessService;
use App\Http\Requests\AccessRequest;

class AccessController extends Controller
{
    protected $accessService;

    public function __construct(
        AccessService $accessService
    ){
        $this->accessService = $accessService;
    }

    // ========================================================================================================
    // ============================================= User Access  =============================================
    // ========================================================================================================
    public function viewUserAccess(Request $request){
        $category = $request->category;
        return $this->accessService->getUserAccessForDataTableService($category);
    }

    public function createUpdateUserAccess(AccessRequest $request){
        $userAccessId = $request->user_access_id;
        $data = $request->only(['description', 'category', 'details']);
        $result = $this->accessService->createUpdateUserAccessService($userAccessId, $data);

        return response()->json($result);
    }
    
    public function getUserAccessInfoById(Request $request){
        $userAccessId = $request->userAccessId;
        $userAccessInfo = $this->accessService->getUserAccessInfoByIdService($userAccessId);
        return response()->json(['requestUserAccessInfo' => $userAccessInfo]);
    }

    public function changeUserAccessStatus(Request $request){
        $result = $this->accessService->changeUserAccessStatusService($request->all());
        if ($result['hasError'] === 0) {
            return response()->json(['hasError' => 0]);
        }else{
            return response()->json([
                'hasError' => 1,
                'exceptionError' => $result['exceptionError'] ?? 'An unknown error occurred.',
            ], 500);
        }
    }

    // ========================================================================================================
    // ========================================= User Access Details ==========================================
    // ========================================================================================================
    public function viewUserAccessDetails(Request $request){
        $userAccessId = $request->userAccessId;
        return $this->accessService->getUserAccessDetailsForDataTableService($userAccessId);
    }

    public function createUpdateUserAccessDetails(AccessRequest $request){
        $userAccessId = $request->user_access_details_id;
        $data = $request->only(['get_access_id','description']);

        $result = $this->accessService->createUpdateUserAccessDetailsService($userAccessId, $data);

        return response()->json($result);
    }

    public function changeAccessDetailsStatus(Request $request){
        $result = $this->accessService->changeAccessDetailsStatusService($request->all());
        if ($result['hasError'] === 0) {
            return response()->json(['hasError' => 0]);
        }else{
            return response()->json([
                'hasError' => 1,
                'exceptionError' => $result['exceptionError'] ?? 'An unknown error occurred.',
            ], 500);
        }
    }
}
