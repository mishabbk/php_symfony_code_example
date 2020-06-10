<?php

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityRemoteType.
 */
class EntityRemoteType extends AbstractType
{
    /**
     * @return string
     */
    public function getParent()
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'route'          => null,
                'pagination'     => true,
                'custom_filters' => [],
            ]
        );
    }
}
