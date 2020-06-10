<?php

namespace App\Modele;

use App\Entity\Property\Property;

trait SearchPropertyTrait
{
    /**
     * @var Property|null
     */
    private $property;

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }
}
