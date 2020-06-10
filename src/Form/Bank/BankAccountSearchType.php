<?php

namespace App\Form\Bank;

use App\Form\Type\DateRangeType;
use App\Modele\Bank\FilterSearchBankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BankAccountSearchType.
 */
class BankAccountSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'search',
                TextType::class,
                [
                    'label' => false,
                    'attr'  => [
                        'placeholder' => 'search',
                    ],
                ]
            )
            ->add(
                'bank',
                BankFieldType::class,
                [
                    'label'       => false,
                    'required'    => false,
                    'placeholder' => 'bank.account.bank',
                ]
            )
            ->add(
                'period',
                DateRangeType::class,
                [
                    'label' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => FilterSearchBankAccount::class,
                'method'          => 'GET',
                'csrf_protection' => false,
                'required'        => false,
            ]
        );
    }
}
