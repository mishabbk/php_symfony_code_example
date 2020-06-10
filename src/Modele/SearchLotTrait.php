<?php

namespace App\Modele;

use App\Entity\Property\Lot;

trait SearchLotTrait
{
    /**
     * @var Lot|int|null
     */
    private $lot;

    /**
     * @return Lot|int|null
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * @param Lot|int|null $lot
     *
     * @return SearchLotTrait
     */
    public function setLot($lot)
    {
        $this->lot = $lot;

        return $this;
    }
}
