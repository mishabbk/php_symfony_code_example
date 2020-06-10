<?php

namespace App\Modele;

use App\Entity\Person;

trait SearchPersonTrait
{
    /**
     * @var Person|null
     */
    private $person;

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
