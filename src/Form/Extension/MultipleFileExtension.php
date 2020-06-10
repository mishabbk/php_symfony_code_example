<?php

namespace App\Form\Extension;

use App\Form\Type\MultipleFileType;

/**
 * Class MultipleFileExtension.
 */
class MultipleFileExtension extends FileExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [MultipleFileType::class];
    }
}
