<?php

namespace App\Repository\Document;

use App\Entity\Document\DocumentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DocumentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentType[]    findAll()
 * @method DocumentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTypeRepository extends ServiceEntityRepository
{
    /**
     * DocumentTypeRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentType::class);
    }

    /**
     * @return int|mixed|string|DocumentType[]
     */
    public function getAllForEntity(?string $entity, ?bool $required = null)
    {
        $queryBuilder = $this->createQueryBuilder('document_type')
            ->addSelect('typeToEntity')
            ->innerJoin('document_type.typeToEntities', 'typeToEntity')
            ->andWhere('typeToEntity.entity = :entity')
            ->setParameter('entity', $entity);

        if (null !== $required) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('typeToEntity.required', ':required')
            )->setParameter(
                'required',
                $required
            );
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
