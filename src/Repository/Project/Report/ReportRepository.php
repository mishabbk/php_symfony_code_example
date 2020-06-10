<?php

namespace App\Repository\Project\Report;

use App\Entity\Project\Report\Report;
use App\Modele\Project\Report\FilterSearchReport;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginator)
    {
        parent::__construct($registry, Report::class);
        $this->paginator = $paginator;
    }

    public function search(FilterSearchReport $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('report');

        $queryBuilder->addOrderBy('report.id', 'DESC');

        $this->addConditions($queryBuilder, $filter);

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page,
            );
    }

    /**
     * Add conditions.
     */
    private function addConditions(QueryBuilder $queryBuilder, FilterSearchReport $filter): void
    {
        if (null !== $filter->getProject()) {
            $queryBuilder->andWhere(
                'report.project = :project'
            )->setParameter(
                'project',
                $filter->getProject()
            );
        }
    }
}
