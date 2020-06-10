<?php

namespace App\Form\Extension;

use App\Form\Type\MultipleEntityRemoteType;

/**
 * Class MultipleEntityRemoteTypeExtension.
 */
class MultipleEntityRemoteTypeExtension extends EntityRemoteExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [MultipleEntityRemoteType::class];
    }
}
