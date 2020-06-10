<?php

namespace App\Modele;

use App\Entity\Bank\BankAccount;

trait SearchBankAccountTrait
{
    /**
     * @var BankAccount|null
     */
    private $bankAccount;

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }
}
