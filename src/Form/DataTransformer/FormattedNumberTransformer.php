<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class FormattedNumberTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        return str_replace(',', '.', $value);
    }

    public function reverseTransform($value): string
    {
        if (!preg_match('/[0-9]/', $value)) {
            return '';
        }

        return preg_replace('/[^-0-9,.]/', '', $value);
    }
}
