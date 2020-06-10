<?php

namespace App\Modele;

use App\Entity\Property\Lot;

trait SearchLotImageTrait
{
    /**
     * @var Lot|null
     */
    private $lotImage;

    public function getLotImage(): ?Lot
    {
        return $this->lotImage;
    }

    public function setLotImage(?Lot $lot): self
    {
        $this->lotImage = $lot;

        return $this;
    }
}
