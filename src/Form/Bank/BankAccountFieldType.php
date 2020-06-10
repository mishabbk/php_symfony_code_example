<?php

namespace App\Form\Bank;

use App\Entity\Bank\BankAccount;
use App\Form\Type\EntityFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountFieldType extends AbstractType
{
    public function getParent(): string
    {
        return EntityFieldType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => BankAccount::class,
                'route' => 'dashboard_bank_account_autocomplete',
                'label' => 'bank.bank_account',
            ]
        );
    }
}
