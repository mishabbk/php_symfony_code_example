<?php

namespace App\Repository\Document;

use App\Entity\Document\TypeToEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeToEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeToEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeToEntity[]    findAll()
 * @method TypeToEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeToEntityRepository extends ServiceEntityRepository
{
    /**
     * TypeToEntityRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeToEntity::class);
    }
}
