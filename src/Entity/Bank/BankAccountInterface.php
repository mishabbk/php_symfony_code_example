<?php

namespace App\Entity\Bank;

interface BankAccountInterface
{
    public function getBankAccount(): ?BankAccount;

    public function setBankAccount(?BankAccount $bankAccount);
}
