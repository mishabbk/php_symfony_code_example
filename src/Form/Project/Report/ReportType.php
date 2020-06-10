<?php

namespace App\Form\Project\Report;

use App\Entity\Person;
use App\Entity\Project\Report\Report;
use App\Form\Type\DragAndDropMultipleFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'person',
                EntityType::class,
                [
                    'class'       => Person::class,
                    'label'       => 'project.report.person',
                    'required'    => true,
                    'placeholder' => 'project.report.person',
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'project.report.title',
                    'attr'  => [
                        'placeholder' => 'project.report.title',
                    ],
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'project.report.content',
                    'attr'  => [
                        'placeholder' => 'project.report.content',
                        'rows'        => 10,
                    ],
                ]
            )
            ->add(
                'documents',
                DragAndDropMultipleFileType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Report::class,
            ]
        );
    }
}
