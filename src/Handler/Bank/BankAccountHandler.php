<?php

namespace App\Handler\Bank;

use App\Modele\Bank\FilterSearchBankAccount;
use App\Repository\Bank\BankAccountRepository;
use App\Service\Pagination\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class BankAccountHandler
{
    /**
     * @var BankAccountRepository
     */
    private $repository;

    public function __construct(BankAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findBankByUserQuery(ParameterBag $queryParameters): Paginator
    {
        $filter = new FilterSearchBankAccount();

        $filter->setSearch($queryParameters->get('term', null));

        return $this->repository->search($filter);
    }
}
