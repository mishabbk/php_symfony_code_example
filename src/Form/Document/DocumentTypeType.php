<?php

namespace App\Form\Document;

use App\Entity\Document\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DocumentTypeType.
 */
class DocumentTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'document.type.name',
                    'attr'  => [
                        'placeholder' => 'document.type.name',
                    ],
                ]
            )
            ->add(
                'typeToEntities',
                CollectionType::class,
                [
                    'entry_type'    => TypeToEntityType::class,
                    'entry_options' => ['label' => false],
                    'by_reference'  => false,
                    'allow_add'     => true,
                    'label'         => false,
                    'allow_delete'  => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DocumentType::class,
            ]
        );
    }
}
