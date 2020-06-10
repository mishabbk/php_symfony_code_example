<?php

namespace App\Validator\InvestorToBond;

use Symfony\Component\Validator\Constraint;

/**
 * Class MaxQuantity.
 *
 * @Annotation
 */
class MaxQuantity extends Constraint
{
    public $message              = 'Cannot be superior to bond\'s quantity';
    public $messageMaxAttributed = 'Cannot be superior to attributed bond\'s quantity ({{value}} left)';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
