<?php

namespace App\Handler\Bank;

use App\Modele\Bank\FilterSearchBank;
use App\Repository\Bank\BankRepository;
use App\Service\Pagination\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class BankHandler
{
    /**
     * @var BankRepository
     */
    private $repository;

    public function __construct(BankRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findBankByUserQuery(ParameterBag $queryParameters): Paginator
    {
        $filter = new FilterSearchBank();
        $filter->setSearch($queryParameters->get('term', null));

        return $this->repository->search($filter);
    }
}
