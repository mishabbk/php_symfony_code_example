<?php

namespace App\Form\Bank;

use App\Entity\Bank\Bank;
use App\Form\Type\EntityFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankFieldType extends AbstractType
{
    public function getParent(): string
    {
        return EntityFieldType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => Bank::class,
                'route' => 'bank_autocomplete',
                'label' => 'bank.bank',
            ]
        );
    }
}