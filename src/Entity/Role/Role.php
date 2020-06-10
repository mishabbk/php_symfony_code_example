<?php

namespace App\Entity\Role;

use App\Entity\Employee\Type;
use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="App\Repository\Role\RoleRepository")
 *
 * @UniqueEntity("name")
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(name="associate", type="boolean", options={"default": 0})
     */
    private $associate = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Employee\Type", inversedBy="roles")
     */
    private $employeeType;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="customRoles")
     */
    private $persons;

    public function __construct()
    {
        $this->employeeType = new ArrayCollection();
        $this->persons      = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAssociate(): ?bool
    {
        return $this->associate;
    }

    public function setAssociate(bool $associate): self
    {
        $this->associate = $associate;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getEmployeeType(): Collection
    {
        return $this->employeeType;
    }

    public function addEmployeeType(Type $employeeType): self
    {
        if (!$this->employeeType->contains($employeeType)) {
            $this->employeeType->add($employeeType);
        }

        return $this;
    }

    public function removeEmployeeType(Type $employeeType): self
    {
        if ($this->employeeType->contains($employeeType)) {
            $this->employeeType->removeElement($employeeType);
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons->add($person);
            $person->addCustomRole($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->persons->contains($person)) {
            $this->persons->removeElement($person);
            $person->removeCustomRole($this);
        }

        return $this;
    }
}
