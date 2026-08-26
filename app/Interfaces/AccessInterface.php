<?php

namespace App\Interfaces;

interface AccessInterface
{
    public function getAllUserAccessDataRepository($category);
    public function createUpdateUserAccessRepository(?string $userAccessId, array $data): bool;
    public function existsUserAccessRepository(array $conditions, ?string $excludeUserAccessId = null): bool;
    public function getUserAccessInfoByIdRepository($userAccessId);
    public function changeUserAccessStatusRepository(array $request);
    
    public function getAllUserAccessDetailsDataRepository($userAccessId);
    public function createUpdateUserAccessDetailsRepository(?string $userAccessDetailsId, array $data): bool;
    public function existsUserAccessDetailsRepository(array $conditions, ?string $excludeUserAccessDetailsId = null): bool;
    public function changeAccessDetailsStatusRepository(array $request);

}
