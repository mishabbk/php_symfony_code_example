<?php

namespace App\Repository\Document;

use App\Entity\Document\Document;
use App\Modele\Document\FilterSearchDocument;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    /**
     * DocumentRepository constructor.
     */
    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginator)
    {
        parent::__construct($registry, Document::class);
        $this->paginator = $paginator;
    }

    public function search(FilterSearchDocument $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('document');

        $queryBuilder->addOrderBy('document.id', 'DESC');

        $this->addConditions($queryBuilder, $filter);

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page,
            );
    }

    private function addConditions(QueryBuilder $queryBuilder, FilterSearchDocument $filter): void
    {
        if (null !== $filter->getSearch()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('document.name', ':search'),
                    $queryBuilder->expr()->like('document.fileName', ':search'),
                    $queryBuilder->expr()->like('document.originalName', ':search')
                )
            )->setParameter(
                ':search',
                '%'.preg_replace('/[^A-Z0-9]/i', '%', $filter->getSearch()).'%'
            );
        }
        if (null !== $filter->getSas()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.companySas', ':sas_company')
            )->setParameter(
                ':sas_company',
                $filter->getSas()
            );
        }

        if (null !== $filter->getProject()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.project', ':project')
            )->setParameter(
                ':project',
                $filter->getProject()
            );
        }

        if (null !== $filter->getProperty()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.property', ':property')
            )->setParameter(
                ':property',
                $filter->getProperty()
            );
        }

        if (null !== $filter->getLot()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.lot', ':lot')
            )->setParameter(
                ':lot',
                $filter->getLot()
            );
        }

        if (null !== $filter->getLotImage()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.lotImage', ':lotImage')
            )->setParameter(
                ':lotImage',
                $filter->getLotImage()
            );
        }

        if (null !== $filter->getPerson()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.person', ':person')
            )->setParameter(
                ':person',
                $filter->getPerson()
            );
        }

        if (null !== $filter->getBond()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('document.bond', ':bond')
            )->setParameter(
                ':bond', $filter->getBond()
            );
        }
    }
}
