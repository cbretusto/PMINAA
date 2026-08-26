<?php

namespace App\Services;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Interfaces\AccessInterface;
use Yajra\DataTables\Facades\DataTables;

class AccessService
{
    protected $accessInterface;

    public function __construct(
        AccessInterface $accessInterface
    ){
        $this->accessInterface = $accessInterface;
    }

    // ============================================================================================================================================================
    // ======================================================================= User Access  =======================================================================
    // ============================================================================================================================================================
    public function getUserAccessForDataTableService($category){
        $user_access = $this->accessInterface->getAllUserAccessDataRepository($category);
        return DataTables::of($user_access)
        ->addColumn('action', function ($access) {
            $btns = '<div class="d-flex justify-content-center">';
            $btns .= '<div class="d-flex align-items-center">';

                if($access->status == 0){
                    $btns .= '<div style="width:40px; text-align:center;">';
                    $btns .= '<button type="button"
                                class="btn btn-dark btn-sm actionUpdateUserAccess"
                                access-id="' . $access->id . '"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCreateUpdateUserAccess"
                                title="Update User Access">
                                <i class="fas fa-edit"></i>
                            </button>';
                    $btns .= '</div>';

                    $btns .= '<div style="width:40px; text-align:center;">';
                    $btns .= '<button type="button"
                                class="btn btn-danger btn-sm actionUserAccessChangeStatus"
                                access-id="' . $access->id . '"
                                status="1"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUserAccessChangeStatus"
                                title="Deactivate User Access">
                                <i class="fas fa-power-off"></i>
                            </button>';
                    $btns .= '</div>';

                    $btns .= '<div style="width:40px; text-align:center;">';
                    if($access->details == '0'){
                        $btns .= '<button type="button"
                                    class="btn btn-info btn-sm actionUserAccessDetails"
                                    access-id="' . $access->id . '"
                                    access-description="' . $access->description . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCreateUpdateUserAccessDetails"
                                    title="User Access Details">
                                    <i class="fas fa-cog"></i>
                                </button>';
                    }
                    $btns .= '</div>';
                }else{
                    $btns .= '<button type="button"
                                class="btn btn-warning btn-sm actionUserAccessChangeStatus"
                                access-id="' . $access->id . '"
                                status="0"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUserAccessChangeStatus"
                                title="Activate User Access">
                                <i class="fa-solid fa-arrow-rotate-right"></i>
                            </button>';
                }
            $btns .= '</div>';
            $btns .= '</div>';

            return $btns;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function createUpdateUserAccessService(?string $userAccessId, array $data): array{
        return DB::transaction(function () use ($userAccessId, $data) {
            if ($this->accessInterface->existsUserAccessRepository(['description' => $data['description'], 'category' => $data['category'], 'details' => $data['details'], 'logdel' => 0], $userAccessId)) {
                return ['hasError' => 1, 'message' => 'User Access already exists'];
            }

            $this->accessInterface->createUpdateUserAccessRepository($userAccessId, $data);

            return ['hasError' => 0];
        }, 5);
    }

    public function getUserAccessInfoByIdService($userAccessId){
        return $this->accessInterface->getUserAccessInfoByIdRepository($userAccessId);
    }

    public function changeUserAccessStatusService(array $data): array{
        return DB::transaction(function () use ($data) {
            $this->accessInterface->changeUserAccessStatusRepository($data);
            return ['hasError' => 0];
        }, 5);
    }

    // ======================================================================================================================================================
    // ================================================================ User Access Details =================================================================
    // ======================================================================================================================================================
    public function getUserAccessDetailsForDataTableService($userAccessId){
        $user_access_details = $this->accessInterface->getAllUserAccessDetailsDataRepository($userAccessId);

        return DataTables::of($user_access_details)
        ->addColumn('action', function ($access_detail) {
            $btns = '<center>';
                if($access_detail->status == 0){
                    $btns .= '<button type="button" class="btn btn-dark btn-sm actionUpdateUserAccessDetails" access_details-id="' . $access_detail->id . '" access-id="' . $access_detail->accesses_id . '"  access_details-description="' . $access_detail->description . '" title="Update Access Details"><i class="fas fa-edit"></i></button>&nbsp;';
                    $btns .= '<button type="button" class="btn btn-danger btn-sm actionUserAccessDetailsChangeStatus" access_details-id="' . $access_detail->id . '" status="1" title="Deactivate Access Details"><i class="fas fa-power-off"></i></button>';
                }else{
                    $btns .= '<button type="button" class="btn btn-warning btn-sm actionUserAccessDetailsChangeStatus" access_details-id="' . $access_detail->id . '" status="0" title="Activate Access Details"><i class="fa-solid fa-arrow-rotate-right"></i></button>';
                }
            $btns .= '</center>';
            return $btns;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function createUpdateUserAccessDetailsService(?string $userAccessId, array $data): array{
        return DB::transaction(function () use ($userAccessId, $data) {
            if ($this->accessInterface->existsUserAccessRepository(['description' => $data['description'], 'logdel' => 0], $userAccessId)) {
                return ['hasError' => 1, 'message' => 'Access Details already exists'];
            }

            $this->accessInterface->createUpdateUserAccessDetailsRepository($userAccessId, $data);

            return ['hasError' => 0];
        }, 5);
    }

    public function changeAccessDetailsStatusService(array $data): array{
        return DB::transaction(function () use ($data) {
            $this->accessInterface->changeAccessDetailsStatusRepository($data);
            return ['hasError' => 0];
        }, 5);
    }
}
