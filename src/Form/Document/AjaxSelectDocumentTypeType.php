<?php

namespace App\Form\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjaxSelectDocumentTypeType extends AbstractType
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
                    'placeholder' => 'document.type',
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
