<?php

namespace App\Repositories;

use App\Models\EmployeeAccount;

class UserAccountRepository
{
    public function store($validatedData)
    {
        return EmployeeAccount::create($validatedData)->fresh();
    }

    public function createOrUpdate($userDetail,$validatedData)
    {
        $account = $userDetail->accountDetail;
        if ($account) {
            $account->update($validatedData);
        } else {
            $userDetail->accountDetail()->create($validatedData);
        }
    }

}
