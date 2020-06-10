<?php

namespace App\Repository\Bank;

use App\Entity\Bank\Bank;
use App\Modele\Bank\FilterSearchBank;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Bank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bank[]    findAll()
 * @method Bank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    /**
     * BankRepository constructor.
     */
    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginatorFactory)
    {
        parent::__construct($registry, Bank::class);

        $this->paginator = $paginatorFactory;
    }

    public function search(FilterSearchBank $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('bank');

        $this->addConditions($queryBuilder, $filter);

        $queryBuilder->addOrderBy('bank.id', 'DESC');

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page
        );
    }

    private function addConditions(QueryBuilder $queryBuilder, FilterSearchBank $filter): void
    {
        if (null !== $filter->getSearch()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('bank.name', ':search'),
                )
            )->setParameter(
                ':search',
                '%'.preg_replace('/[^A-Z0-9]/i', '%', $filter->getSearch()).'%'
            );
        }
    }
}
