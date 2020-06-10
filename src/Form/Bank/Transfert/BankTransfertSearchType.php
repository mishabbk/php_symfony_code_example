<?php

namespace App\Form\Bank\Transfert;

use App\Entity\Bank\Transfert\Transfert;
use App\Form\Bank\BankAccountFieldType;
use App\Form\ChoiceGroupType;
use App\Modele\Bank\FilterSearchBankTransfert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BankTransfertSearchType.
 */
class BankTransfertSearchType extends AbstractType
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
                'bankAccount',
                BankAccountFieldType::class,
                [
                    'required' => false,
                    'label'    => 'bank.transfert.search.bank_account',
                ]
            )
            ->add(
                'movement',
                ChoiceGroupType::class,
                [
                    'required' => false,
                    'label'    => 'bank.transfert.search.movement',
                    'choices'  => [
                        'bank.transfert.movement.in'  => Transfert::MOVEMENT_IN,
                        'bank.transfert.movement.out' => Transfert::MOVEMENT_OUT,
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => FilterSearchBankTransfert::class,
                'method'          => 'GET',
                'csrf_protection' => false,
                'required'        => false,
            ]
        );
    }
}
