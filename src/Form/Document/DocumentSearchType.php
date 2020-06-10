<?php

namespace App\Form\Document;

use App\Form\Person\PersonFieldType;
use App\Modele\Document\FilterSearchDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentSearchType extends AbstractType
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
                'person',
                PersonFieldType::class,
                [
                    'label' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => FilterSearchDocument::class,
                'method'          => 'GET',
                'csrf_protection' => false,
                'required'        => false,
            ]
        );
    }
}
