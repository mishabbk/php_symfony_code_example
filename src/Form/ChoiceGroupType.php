<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceGroupType.
 */
class ChoiceGroupType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'placeholder'  => 'form.dontknow',
                'multiple'     => false,
                'expanded'     => true,
                'choices'      => [
                    'form.no'  => false,
                    'form.yes' => true,
                ],
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
