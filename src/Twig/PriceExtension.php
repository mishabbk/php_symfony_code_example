<?php

namespace App\Twig;

use App\Service\Price;
use Twig\Extension\AbstractExtension;
use \Twig\TwigFilter;

/**
 * Class PriceExtension.
 */
class PriceExtension extends AbstractExtension
{
    /** @var Price $price */
    private $price;

    /**
     * PriceExtension constructor.
     *
     * @param Price $price
     */
    public function __construct(Price $price)
    {
        $this->price = $price;
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('priceFloat', [$this, 'priceFloatFilter']),
        ];
    }

    /**
     * @param float $price
     * @param int   $decimal
     *
     * @return string
     */
    public function priceFilter($price, int $decimal = 0)
    {
        return $this->price->formatDisplay($price, $decimal);
    }

    /**
     * @param float $price
     *
     * @return string
     */
    public function priceFloatFilter($price)
    {
        return $this->priceFilter($price, 2);
    }
}
