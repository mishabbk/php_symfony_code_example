<?php

namespace App\Twig;

use App\Entity\Person;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class GenderExtension.
 */
class GenderExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * GenderExtension constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('gender', [$this, 'genderFilter']),
            new TwigFilter('genderShort', [$this, 'genderShortFilter']),
        ];
    }

    /**
     * @param bool|null $value
     *
     * @return string
     */
    public function genderFilter($value, bool $forceReturn = true)
    {
        if (Person::GENDER_MALE === $value) {
            return $this->translator->trans(
                'gender.m'
            );
        }
        if (Person::GENDER_FEMALE === $value) {
            return $this->translator->trans(
                'gender.f'
            );
        }

        if ($forceReturn) {
            return $this->translator->trans(
                'gender.both'
            );
        }

        return '';
    }

    /**
     * @param bool|null $value
     *
     * @return string
     */
    public function genderShortFilter($value, bool $forceReturn = true)
    {
        if (Person::GENDER_MALE === $value) {
            return $this->translator->trans(
                'gender.m.short'
            );
        }
        if (Person::GENDER_FEMALE === $value) {
            return $this->translator->trans(
                'gender.f.short'
            );
        }

        if ($forceReturn) {
            return $this->translator->trans(
                'gender.both.short'
            );
        }

        return '';
    }
}
