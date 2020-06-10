<?php

namespace App\Form\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalEditDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                EntityType::class,
                [
                    'label'       => false,
                    'required'    => false,
                    'class'       => DocumentType::class,
                    'placeholder' => 'select something',
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Document::class,
            ]
        );
    }
}
