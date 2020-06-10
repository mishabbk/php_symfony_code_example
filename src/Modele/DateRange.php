<?php

namespace App\Modele;

use DateTime;
use Exception;

/**
 * Class DateRange.
 */
class DateRange
{
    public const DATE_RANGE_FORMAT = 'y-MMMM-dd';

    /** @var DateTime|null */
    private $min;

    /** @var DateTime|null */
    private $max;

    /**
     * DateRange constructor.
     */
    public function __construct(?DateTime $min = null, ?DateTime $max = null)
    {
        if ($min) {
            $this->setMin($min);
        }
        if ($max) {
            $this->setMax($max);
        }
    }

    public function getMin(): ?DateTime
    {
        return $this->min;
    }

    public function setMin(?DateTime $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?DateTime
    {
        return $this->max;
    }

    public function setMax(?DateTime $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasValues()
    {
        return $this->getMin() && $this->getMax();
    }

    /**
     * @param $string
     *
     * @throws Exception
     *
     * @return DateTime
     */
    public function readDate($string)
    {
        return new DateTime($string);
    }
}
