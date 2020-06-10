<?php

namespace App\Form\Project;

use App\Entity\Project\Project;
use App\Form\Type\DragAndDropMultipleFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectDocumentUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'documents',
                DragAndDropMultipleFileType::class, [
                    'label'          => false,
                    'add_files_list' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Project::class,
            ]
        );
    }
}
