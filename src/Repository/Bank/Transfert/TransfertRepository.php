<?php

namespace App\Repository\Bank\Transfert;

use App\Entity\Bank\Transfert\Transfert;
use App\Modele\Bank\FilterSearchBankTransfert;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Transfert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transfert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transfert[]    findAll()
 * @method Transfert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    /**
     * TransfertRepository constructor.
     */
    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginatorFactory)
    {
        parent::__construct($registry, Transfert::class);

        $this->paginator = $paginatorFactory;
    }

    public function search(FilterSearchBankTransfert $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('bt');

        $this->addConditions($queryBuilder, $filter);

        $queryBuilder->addOrderBy('bt.id', 'DESC');

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page
        );
    }

    private function addConditions(QueryBuilder $queryBuilder, FilterSearchBankTransfert $filter): void
    {
        if (null !== $filter->getSearch()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('bt.amount', ':search'),
                    $queryBuilder->expr()->like('bt.reference', ':search'),
                    $queryBuilder->expr()->like('bt.comment', ':search'),
                )
            )->setParameter(
                ':search',
                '%'.preg_replace('/[^A-Z0-9]/i', '%', $filter->getSearch()).'%'
            );
        }
        if (null !== $filter->getBankAccount()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('bt.bankAccount', ':bankAccount')
            )->setParameter(
                ':bankAccount',
                $filter->getBankAccount()->getId()
            );
        }
        if (null !== $filter->getMovement()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('bt.movement', ':movement'),
            )->setParameter(
                ':movement',
                $filter->getMovement()
            );
        }
    }
}
