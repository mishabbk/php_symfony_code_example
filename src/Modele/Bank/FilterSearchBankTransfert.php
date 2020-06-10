<?php

namespace App\Modele\Bank;

use App\Entity\Bank\BankAccount;
use App\Modele\SearchBankAccountTrait;
use App\Modele\SearchTrait;

/**
 * Class FilterSearchBankTransfert.
 */
class FilterSearchBankTransfert
{
    use SearchTrait;
    use SearchBankAccountTrait;
    
    /**
     * @var string|null
     */
    private $movement;

    public function getMovement(): ?string
    {
        return $this->movement;
    }

    public function setMovement(?string $movement): self
    {
        $this->movement = $movement;

        return $this;
    }
}
