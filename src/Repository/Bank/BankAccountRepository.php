<?php

namespace App\Repository\Bank;

use App\Entity\Bank\BankAccount;
use App\Modele\Bank\FilterSearchBankAccount;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method BankAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccount[]    findAll()
 * @method BankAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    /**
     * BankAccountRepository constructor.
     */
    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginatorFactory)
    {
        parent::__construct($registry, BankAccount::class);

        $this->paginator = $paginatorFactory;
    }

    public function search(FilterSearchBankAccount $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('ba');

        $this->addConditions($queryBuilder, $filter);

        $queryBuilder->addOrderBy('ba.id', 'DESC');

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page
        );
    }

    private function addConditions(QueryBuilder $queryBuilder, FilterSearchBankAccount $filter): void
    {
        if (null !== $filter->getSearch()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('ba.iban', ':search'),
                    $queryBuilder->expr()->like('ba.bic', ':search'),
                    $queryBuilder->expr()->like('ba.accountHolder', ':search')
                )
            )->setParameter(
                ':search',
                '%'.preg_replace('/[^A-Z0-9]/', '', $filter->getSearch()).'%'
            );
        }
        if (null !== $filter->getBank()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('ba.bank', ':bank')
            )->setParameter(
                ':bank',
                $filter->getBank()->getId()
            );
        }
        if (null !== $filter->getPeriod()
            && null !== $filter->getPeriod()->getMin()
            && null !== $filter->getPeriod()->getMax()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->between('ba.openingDate', ':dateFrom', ':dateTo')
            )->setParameter(
                ':dateFrom',
                $filter->getPeriod()->getMin()
            )->setParameter(
                ':dateTo',
                $filter->getPeriod()->getMax()
            );
        }
    }
}
