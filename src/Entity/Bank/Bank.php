<?php

namespace App\Entity\Bank;

use App\Entity\Address;
use App\Entity\AddressInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bank")
 * @ORM\Entity(repositoryClass="App\Repository\Bank\BankRepository")
 */
class Bank implements AddressInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Bank
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @return Bank
     */
    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->name;
    }
}
