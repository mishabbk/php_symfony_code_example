<?php

namespace App\Repository\Project;

use App\Entity\Person;
use App\Entity\Project\Project;
use App\Modele\Project\FilterSearchProject;
use App\Service\Pagination\Paginator;
use App\Service\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginator;

    /**
     * @var Security
     */
    private $security;

    public function __construct(ManagerRegistry $registry, PaginatorFactory $paginator, Security $security)
    {
        parent::__construct($registry, Project::class);
        $this->paginator = $paginator;
        $this->security  = $security;
    }

    public function search(FilterSearchProject $filter, int $page = 1): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('project');

        $queryBuilder->addOrderBy('project.id', 'DESC');

        $this->addSecurityPerson($filter);

        $this->addConditions($queryBuilder, $filter);

        return $this->paginator->getPaginator(
            $queryBuilder,
            $page,
        );
    }

    public function canAccessByPerson(Project $project, Person $person): ?Project
    {
        $queryBuilder = $this
            ->createQueryBuilder('project')
            ->where('project.id = :id')
            ->setParameter('id', $project->getId())
        ;
        $this->wherePerson($queryBuilder, $person);

        return $queryBuilder
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    private function addSecurityPerson(FilterSearchProject $filter): void
    {
        /**
         * @var Person $user
         */
        $user = $this->security->getUser();
        if (!$user->isSuperAdmin()) {
            $filter->setPerson($user);
        }
    }

    private function addConditions(QueryBuilder $queryBuilder, FilterSearchProject $filter): void
    {

        $this->whereSas($queryBuilder, $filter->getSas());
        $this->wherePerson($queryBuilder, $filter->getPerson());

        if (null !== $filter->getSearch()) {
            if (null !== $filter->getSearch()) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('project.name', ':search')
                    )
                )->setParameter(
                    ':search',
                    '%'.preg_replace('/[^A-Z0-9]/i', '%', $filter->getSearch()).'%'
                );
            }
        }
    }

    private function joinSas(QueryBuilder $queryBuilder): void {
        if (!in_array('company_sas', $queryBuilder->getAllAliases(), true)) {
            $queryBuilder->leftJoin('project.companySas', 'company_sas');
        }
    }

    private function whereSas(QueryBuilder $queryBuilder, $sas): void
    {
        if (null !== $sas) {
            $this->joinSas($queryBuilder);
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('company_sas.company', ':sas_company')
            )->setParameter(
                ':sas_company',
                $sas
            );
        }
    }

    private function wherePerson(QueryBuilder $queryBuilder, $person): void
    {
        if (null !== $person) {
            $this->joinSas($queryBuilder);
            $queryBuilder->leftJoin(
                'company_sas.associateToSas',
                'associate_to_sas'
            )->leftJoin(
                'associate_to_sas.associate',
                'associate'
            )->leftJoin(
                'project.properties',
                'properties'
            )->leftJoin(
                'properties.bonds',
                'bonds'
            )->leftJoin(
                'project.prospector',
                'prospector'
            )->leftJoin(
                'project.meetings',
                'meetings'
            )->leftJoin(
                'meetings.persons',
                'meeting_person'
            )->leftJoin(
                'project.tickets',
                'tickets'
            )->leftJoin(
                'tickets.persons',
                'ticket_person'
            )->leftJoin(
                'tickets.messages',
                'message'
            )->leftJoin(
                'project.reports',
                'report'
            )->leftJoin(
                'project.employees',
                'employees'
            )->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('associate.person', ':person'),
                    $queryBuilder->expr()->eq('prospector.person', ':person'),
                    $queryBuilder->expr()->eq('meeting_person.id', ':person'),
                    $queryBuilder->expr()->eq('ticket_person.id', ':person'),
                    $queryBuilder->expr()->eq('message.person', ':person'),
                    $queryBuilder->expr()->eq('report.person', ':person'),
                    $queryBuilder->expr()->eq('employees.person', ':person'),
                    )
            )->setParameter(
                ':person',
                $person
            );
        }
    }
}
