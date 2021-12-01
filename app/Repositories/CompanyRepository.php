<?php

namespace App\Repositories;

use App\Models\Manager\Company;

class CompanyRepository
{

    public function firstOrNew(array $data): Company
    {
        $company = Company::firstOrNew(
            ['manager_id' => $data['manager']]
        );
        $company->name  = $data['name'];
        $company->nif   = $data['nif'];
        $company->phone = $data['phone'];
        $company->save();
        return $company;
    }
}
