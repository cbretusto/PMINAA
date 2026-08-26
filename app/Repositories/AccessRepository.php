<?php
namespace App\Repositories;

use App\Interfaces\AccessInterface;
use App\Models\Access;
use App\Models\AccessDetails;
use Illuminate\Support\Collection;

class AccessRepository implements AccessInterface
{
    // ========================================================================================================
    // ============================================= User Access  =============================================
    // ========================================================================================================
    public function getAllUserAccessDataRepository($category){
        return Access::where('logdel', 0)
        ->where('category', $category)
        ->get();
    }

    public function createUpdateUserAccessRepository(?string $userAccessId, array $data): bool{
        $userAccessData = $this->mapUserAccessData($data);
        if (empty($userAccessId)) {
            $userAccessData['created_at'] = now();
            return Access::insert($userAccessData);
        } else {
            $userAccessData['updated_at'] = now();
            $userAccess = Access::findOrFail($userAccessId);

            return $userAccess->update($userAccessData);
        }
    }

    private function mapUserAccessData(array $data): array{
        return [
            'description'     => $data['description'],
            'category'        => $data['category'],
            'details'        => $data['details'],
        ];
    }

    public function existsUserAccessRepository(array $conditions, ?string $excludeUserAccessId = null): bool{
        $query = Access::where($conditions);

        if ($excludeUserAccessId) {
            $query->where('id', '!=', $excludeUserAccessId);
        }

        return $query->exists();
    }

    public function getUserAccessInfoByIdRepository($userAccessId){
        return Access::where('id', $userAccessId)
            ->where('logdel', 0)
            ->get();
    }

    public function changeUserAccessStatusRepository(array $request){
        return Access::where('id', $request['user_access_id'])->update([
            'status'         => $request['status'],
            'updated_at'     => now(),
        ]);
    }

    // ========================================================================================================
    // ========================================= User Access Details ==========================================
    // ========================================================================================================
    public function getAllUserAccessDetailsDataRepository($userAccessId){
        return AccessDetails::where('accesses_id', $userAccessId)
        ->where('logdel', 0)
        ->get();
    }

    public function createUpdateUserAccessDetailsRepository(?string $userAccessDetailsId, array $data): bool{
        $userAccessDetailsData = $this->mapUserAccessDetailsData($data);

        if (empty($userAccessDetailsId)) {
            $userAccessDetailsData['created_at'] = now();
            return AccessDetails::insert($userAccessDetailsData);
        } else {
            $userAccessDetailsData['updated_at'] = now();
            $userAccessDetails = AccessDetails::findOrFail($userAccessDetailsId);

            return $userAccessDetails->update($userAccessDetailsData);
        }
    }

    private function mapUserAccessDetailsData(array $data): array{
        return [
            'accesses_id'    => $data['get_access_id'],
            'description'     => $data['description'],
        ];
    }

    public function existsUserAccessDetailsRepository(array $conditions, ?string $excludeUserAccessDetailsId = null): bool{
        $query = AccessDetails::where($conditions);

        if ($excludeUserAccessDetailsId) {
            $query->where('id', '!=', $excludeUserAccessDetailsId);
        }

        return $query->exists();
    }

    public function changeAccessDetailsStatusRepository(array $request){
        return AccessDetails::where('id', $request['access_details_id'])->update([
            'status'         => $request['status'],
            'updated_at'     => now(),
        ]);
    }
}
