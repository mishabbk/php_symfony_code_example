<?php

namespace App\Modele;

use App\Entity\Bank\Bank;

trait SearchBankTrait
{
    /**
     * @var Bank|null
     */
    private $bank;

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }
}
