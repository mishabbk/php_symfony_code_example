<?php

namespace App\Form\Bank;

use App\Modele\Bank\FilterSearchBank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchType.
 */
class SearchType extends AbstractType
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
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => FilterSearchBank::class,
                'method'          => 'GET',
                'csrf_protection' => false,
                'required'        => false,
            ]
        );
    }
}
