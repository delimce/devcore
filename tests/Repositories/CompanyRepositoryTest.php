<?php

namespace Tests\Repositories;

use App\Models\Manager\Company;
use App\Models\Manager\Manager;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    protected $companyRepository;
    protected $manager;
    protected $company;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
        $this->company = Company::factory()->create();
        $this->companyRepository = $this->app->make('App\Repositories\CompanyRepository');
    }

    public function testFirstOrNewCompany()
    {
        $data = [];
        $data["name"] = $this->company->name;
        $data["nif"] = $this->company->nif;
        $data["phone"] = $this->company->phone;
        $data["manager"] = $this->manager->id;

        $newCompany = $this->companyRepository->firstOrNew($data);
        $this->assertEquals($this->company->name, $newCompany->name);
    }

}