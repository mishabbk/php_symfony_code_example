<?php

namespace App\Modele;

use App\Entity\Company\Sas;

trait SearchSasTrait
{
    /**
     * @var Sas|null
     */
    private $sas;

    /**
     * @return Sas|null
     */
    public function getSas(): ?Sas
    {
        return $this->sas;
    }

    /**
     * @param Sas|null $sas
     * @return SearchSasTrait
     */
    public function setSas(?Sas $sas): self
    {
        $this->sas = $sas;

        return $this;
    }
}
