<?php

namespace App\Extension;

use App\Form\Type\MultipleFileType;

/**
 * Class MultipleFileExtension.
 */
class MultipleFileExtension extends FileExtension
{
    public static function getExtendedTypes(): array
    {
        return [MultipleFileType::class];
    }
}
