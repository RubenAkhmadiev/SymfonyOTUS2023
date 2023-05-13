<?php

namespace App\Adapter;

use App\Customer\Service\CreateCustomerService;

class CustomerAdapter
{
    protected CreateCustomerService $createCustomerService;

    public function createCustomer()
    {
        return $this->createCustomerService->createCustomer();
    }
}
