<?php

namespace App\Modele;

use App\Entity\Bond\Bond;

trait SearchBondTrait
{
    /**
     * @var Bond|null
     */
    private $bond;

    /**
     * @return Bond|null
     */
    public function getBond(): ?Bond
    {
        return $this->bond;
    }

    /**
     * @param Bond|null $bond
     *
     * @return SearchBondTrait
     */
    public function setBond(?Bond $bond): self
    {
        $this->bond = $bond;

        return $this;
    }
}
