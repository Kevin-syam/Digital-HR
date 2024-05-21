<?php

namespace App\Repositories;

use App\Models\Router;

class RouterRepository
{
    const IS_ACTIVE = 1;

    public function getAllRouters($select=['*'],$with=[])
    {
        return Router::with($with)->select($select)->latest()->paginate(Router::RECORDS_PER_PAGE);
    }

    public function getAllBranchActiveRouters($select=['*'])
    {
        return Router::where('is_active',self::IS_ACTIVE)->get();
    }

    public function findRouterDetailByBranchId($authUserBranchId,$with=[],$select=['*'])
    {
        return Router::with($with)
                    ->select($select)
                    ->where('is_active',self::IS_ACTIVE)
                    ->where('branch_id',$authUserBranchId)
                    ->first();
    }

    public function findRouterDetailBSSID($routerBSSID)
    {
        return Router::where('is_active',self::IS_ACTIVE)
            ->where('router_ssid',$routerBSSID)
            ->first();
    }

    public function store($validatedData)
    {
        return Router::create($validatedData)->fresh();
    }

    public function findRouterDetailById($id)
    {
        return Router::where('id',$id)->first();
    }

    public function delete($routerDetail)
    {
        return $routerDetail->delete();
    }

    public function update($routerDetail,$validatedData)
    {
        return $routerDetail->update($validatedData);
    }

    public function toggleStatus($id)
    {
        $routerDetail = Router::where('id',$id)->first();
        return $routerDetail->update([
            'is_active' => !$routerDetail->is_active,
        ]);
    }
}
