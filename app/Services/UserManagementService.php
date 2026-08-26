<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserManagementInterface;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;

class UserManagementService{
    protected $userInterface;

    public function __construct(
        UserManagementInterface $userInterface
    ){
        $this->userInterface = $userInterface;
    }

    // -------------------------------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------- USER MANAGEMENT ----------------------------------------------------------
    // -------------------------------------------------------------------------------------------------------------------------------------
    public function getUsersForDataTableService(){
        $this->updateResignedUsersStatus();

        $users = $this->userInterface->getAllUsersDataRepository();

        return DataTables::of($users)
        ->addColumn('action', function ($user) {
            $btns = '<center>';
                        if ($user->status == 0) {
                            $btns .= '<button type="button" class="btn btn-dark btn-sm actionUpdateUserManagement" user-id="' . $user->id . '" data-bs-toggle="modal" data-bs-target="#modalCreateUpdateUserManagement" title="Update User"><i class="fas fa-edit"></i></button>&nbsp;';
                            $btns .= '<button type="button" class="btn btn-danger btn-sm actionUserManagementChangeStatus" user-id="' . $user->id . '" status="1" data-bs-toggle="modal" data-bs-target="#modalUserManagementChangeUserStatus" title="Deactivate User"><i class="fas fa-power-off"></i></button>';
                        } else {
                            $btns .= '<button type="button" class="btn btn-warning btn-sm actionUserManagementChangeStatus" user-id="' . $user->id . '" status="0" data-bs-toggle="modal" data-bs-target="#modalUserManagementChangeUserStatus" title="Activate User"><i class="fa-solid fa-arrow-rotate-right"></i></button>';

                        }
            $btns .= '</center>';
            return $btns;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function updateResignedUsersStatus(){
        $users = $this->userInterface->getAllUsersDataRepository();

        foreach ($users as $user) {
            $noEmployeeInfo = empty($user->user_management_rapidx_user_info->rapidx_systemone_employee_info);

            if ($noEmployeeInfo) {
                $this->changeUserStatusService([
                    'user_id' => $user->id,
                    'status'  => 2,
                ]);
            }
        }
    }

    public function getRapidxUserActiveInSystemOneService(){
        return $this->userInterface->getRapidxUserActiveInSystemOneRepository();
    }

    public function getSystemOneDepartmentService(){
        return $this->userInterface->getSystemOneDepartmentRepository();
    }

    public function getSystemOnePositionService(){
        return $this->userInterface->getSystemOnePositionRepository();
    }

    public function createOrUpdateUserService(?string $userId, array $data): array{
        return DB::transaction(function () use ($userId, $data) {
            if ($this->userInterface->existsUserRepository(['rapidx_user_id' => $data['name_w_id'], 'logdel' => 0], $userId)) {
                return ['hasError' => 1, 'message' => 'User already exists'];
            }

            $this->userInterface->createOrUpdateUserRepository($userId, $data);

            return ['hasError' => 0];
        }, 5);
    }

    public function getUserInfoByIdService($userId){
        return $this->userInterface->getUserInfoByIdRepository($userId);
    }

    public function changeUserStatusService(array $data): array{
        return DB::transaction(function () use ($data) {
            $this->userInterface->changeUserStatusRepository($data);
            return ['hasError' => 0];
        }, 5);
    }

    // -------------------------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------- USER APPROVER -----------------------------------------------------------
    // -------------------------------------------------------------------------------------------------------------------------------------
    public function getUserApproversForDataTableService(){
        $userDetails = $this->userInterface->getAllUserApproversDataRepository();
        return DataTables::of($userDetails)
            ->addColumn('action', function ($userDetail) {
                $btns = '<center>';
                $btns .= '<button type="button" class="btn btn-dark btn-sm actionUserManagementUpdateUserApprover" user-id="' . $userDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalCreateUpdateUserApprover" title="Update Approver"><i class="fa-solid fa-edit"></i></button>&nbsp;';
                $btns .= '<button type="button" class="btn btn-danger btn-sm actionUserManagementRemoveUserApprover" user-id="' . $userDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalUserManagementRemoveUserApprover" title="Remove Approver"><i class="fa-solid fa-trash-can"></i></button>';
                return $btns . '</center>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getInfoFromUserManagementService(){
        return $this->userInterface->getInfoFromUserManagementRepository();
    }

    public function createUpdateUserApproverService(?string $userId, array $data): array{
        return DB::transaction(function () use ($userId, $data) {
            $this->userInterface->createUpdateUserApproverRepository($userId, $data);

            return ['hasError' => 0];
        }, 5);
    }

    public function getUserApproverInfoByIdService($userId){
        return $this->userInterface->getUserApproverInfoByIdRepository($userId);
    }

    public function removeUserApproverService(array $data): array{
        return DB::transaction(function () use ($data) {
            $this->userInterface->removeUserApproverRepository($data);
            return ['hasError' => 0];
        }, 5);
    }

}
