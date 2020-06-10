<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceRemoteType.
 */
class ChoiceRemoteType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'choice_remote';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'custom_filters' => [],
            ]
        );
    }
}
