<?php

namespace App\Interfaces;

interface PminaaRequestInterface
{
    public function getAllPminaaRequestDataRepository($request, $rapidx_user_id, $rapidx_department_id, array $pminaa_access_for_conformance);
    public function getSystemonePmiSubconEmployeeRepository($user_type);
    public function getEmployeeInfoRepository($employee_no, $user_type);
    public function getPminaaApproverRepository();
    public function getAccountSystemFolderAccessRepository();
    public function getAccountSystemFolderNameRepository($get_access_id, $get_system_module);
    public function createUpdatePminaaRequestRepository(?string $pminaaId, array $data, $rapidx_user_id): bool;
    public function getPminaaRequestInfoByIdRepository($pminaaId);
    public function pminaaRequestChangeApprovalStatusRepository(array $request, $rapidx_user_id);
    public function getConformanceApprovalRepository();
    public function viewPdfPminaaRequestRepository($id);

}
