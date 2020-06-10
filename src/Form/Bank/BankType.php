<?php

namespace App\Form\Bank;

use App\Entity\Bank\Bank;
use App\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'bank.name',
                    'attr'  => [
                        'placeholder' => 'bank.name',
                    ],
                ]
            )
            ->add(
                'address',
                AddressType::class,
                [
                    'label' => 'bank.address',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Bank::class,
            ]
        );
    }
}
