<?php

namespace App\Service\Person;

use App\Entity\Company\Associate\Associate;
use App\Entity\Employee\Employee;
use App\Entity\Meeting;
use App\Entity\Person;
use App\Entity\Project\Project;
use App\Entity\Ticket\Ticket;
use App\Repository\Project\ProjectRepository;
use App\Repository\Role\RoleRepository;

class PersonService
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * PersonService constructor.
     */
    public function __construct(RoleRepository $roleRepository, ProjectRepository $projectRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param Person $person
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole(Person $person, string $roleName): bool
    {
        return $person->hasCustomRole($roleName);
    }

    /**
     * @param Person $person
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRoleInTypes(Person $person, string $roleName): bool
    {
        if ($this->isEmployee($person)) {
            foreach ($person->getEmployee()->getEmployeeType() as $type) {
                if ($type->hasRole($roleName)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Person $person
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRoleAssociate(Person $person, string $roleName): bool
    {
        if ($this->isAssociate($person)) {
            $role = $this->roleRepository->findOneBy(['name' => $roleName]);

            return $role->getAssociate();
        }

        return false;
    }

    /**
     * @param Person $person
     * @param Project $project
     *
     * @return bool
     */
    public function canAccessProject(Person $person, Project $project): bool
    {
        $access = $this->projectRepository->canAccessByPerson($project, $person);
        if ($access) {
            return true;
        }

        return false;
    }

    /**
     * @param Person $person
     * @param Ticket $ticket
     *
     * @return bool
     */
    public function canAccessTicket(Person $person, Ticket $ticket): bool
    {
        return $ticket->getPersons()->contains($person);
    }
  
    /**
     * @param Person $person
     * @param Meeting $meeting
     *
     * @return bool
     */
    public function canAccessMeeting(Person $person, Meeting $meeting): bool
    {
        return $meeting->getPersons()->contains($person);
    }

    /**
     * @param Person $person
     * @return bool
     */
    private function isAssociate(Person $person): bool
    {
        return ($person->getAssociate() instanceof Associate);
    }

    /**
     * @param Person $person
     * @return bool
     */
    private function isEmployee(Person $person): bool
    {
        return ($person->getEmployee() instanceof Employee);
    }
}
