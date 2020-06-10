<?php

namespace App\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeDiffExtension extends AbstractExtension
{
    public static $units = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    public function getFilters()
    {
        return [
            new TwigFilter(
                'time_diff',
                [$this, 'diff'],
                [
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function diff(Environment $env, $date, $now = null)
    {
        $date = twig_date_converter($env, $date);
        $now  = twig_date_converter($env, $now);

        $diff = $date->diff($now);

        foreach (self::$units as $attribute => $unit) {
            $count = $diff->$attribute;

            if (0 !== $count) {
                return $this->getPluralizedInterval($count, $diff->invert, $unit);
            }
        }

        return 'just now';
    }

    private function getPluralizedInterval($count, $invert, $unit)
    {
        if (1 !== $count) {
            $unit .= 's';
        }

        return $invert ? "in $count $unit" : "$count $unit ago";
    }
}
