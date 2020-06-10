<?php

namespace App\Form\Project;

use App\Entity\Project\Project;
use App\Form\Type\EntityFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFieldType extends AbstractType
{
    public function getParent(): string
    {
        return EntityFieldType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => Project::class,
                'route' => 'dashboard_project_autocomplete',
                'label' => 'project.project',
            ]
        );
    }
}
