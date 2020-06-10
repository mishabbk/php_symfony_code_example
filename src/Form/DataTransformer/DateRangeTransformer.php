<?php

namespace App\Form\DataTransformer;

use App\Modele\DateRange;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class DateRangeTransformer.
 */
class DateRangeTransformer implements DataTransformerInterface
{
    /**
     * @param mixed|DateRange|null $dateRange
     *
     * @return array|mixed
     */
    public function transform($dateRange)
    {
        if (null === $dateRange) {
            return ['range' => ''];
        }
        if (false === $dateRange instanceof DateRange) {
            throw new TransformationFailedException(sprintf('Expected a %s', DateRange::class));
        }
        if (!$dateRange->getMin() || !$dateRange->getMax()) {
            return ['range' => null];
        }

        return [
            'range' => $dateRange->getMin()->format(DateRange::DATE_RANGE_FORMAT).
                       ' - '.
                       $dateRange->getMax()->format(DateRange::DATE_RANGE_FORMAT),
        ];
    }

    /**
     * @param array $value
     *
     * @throws Exception
     *
     * @return null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if (preg_match(
            '/\A([0-9-]+)\s*-\s*([0-9-]+)\z/u',
            $value['range'],
            $matches
        )) {
            $dateRange = new DateRange();
            $dateRange->setMin($dateRange->readDate($matches[1]));
            $dateRange->setMax($dateRange->readDate($matches[2]));

            return $dateRange;
        }

        return new DateRange();
    }
}
