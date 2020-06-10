<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DragAndDropMultipleFileType.
 */
class DragAndDropMultipleFileType extends AbstractType
{
    public function getParent(): string
    {
        return MultipleFileType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'drag_and_drop' => true,
                'label'         => 'document.document',
            ]
        );
    }
}
