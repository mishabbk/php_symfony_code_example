<?php

namespace App\Twig;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhoneExtention extends AbstractExtension
{
    /** @var PhoneNumberUtil */
    private $phoneNumberUtil;

    public function __construct(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('format_phone', [$this, 'formatPhone']),
        ];
    }

    public function formatPhone(?PhoneNumber $phone): ?string
    {
        if (null === $phone) {
            return null;
        }

        return $this->phoneNumberUtil->format($phone, PhoneNumberFormat::NATIONAL);
    }
}
