<?php

namespace App\Interfaces;

interface UserManagementInterface
{
    public function getAllUsersDataRepository();
    public function getRapidxUserActiveInSystemOneRepository();
    public function getSystemOneDepartmentRepository();
    public function getSystemOnePositionRepository();
    public function createOrUpdateUserRepository(?string $userId, array $data): bool;
    public function existsUserRepository(array $conditions, ?string $excludeUserId = null): bool;
    public function getUserInfoByIdRepository($userId);
    public function changeUserStatusRepository(array $request);
    
    public function getAllUserApproversDataRepository();
    public function getInfoFromUserManagementRepository();
    public function createUpdateUserApproverRepository(?string $userId, array $data): bool;
    public function getUserApproverInfoByIdRepository($userId);
    public function removeUserApproverRepository(array $request);
}
