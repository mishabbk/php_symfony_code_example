<?php

namespace App\Modele\Bank;

use App\Entity\Bank\Bank;
use App\Modele\DateRange;
use App\Modele\SearchBankTrait;
use App\Modele\SearchTrait;

/**
 * Class FilterSearchBankAccount.
 */
class FilterSearchBankAccount
{
    use SearchTrait;
    use SearchBankTrait;

    /**
     * @var DateRange|null
     */
    private $period;

    public function getPeriod(): ?DateRange
    {
        return $this->period;
    }

    public function setPeriod(?DateRange $period): self
    {
        $this->period = $period;

        return $this;
    }
}
