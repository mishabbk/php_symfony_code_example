<?php

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class BooleanExtension.
 */
class BooleanExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BooleanExtension constructor.
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
            new TwigFilter(
                'boolean',
                [$this, 'booleanFilter']
            ),
            new TwigFilter(
                'boolean_nc',
                [$this, 'booleanFilterNc']
            ),
            new TwigFilter(
                'boolean_icone',
                [$this, 'booleanIconeFilter'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param bool|null $value
     *
     * @return string
     */
    public function booleanFilterNc($value)
    {
        return $this->booleanFilter($value, true);
    }

    /**
     * @param      $value
     * @param bool $nc
     *
     * @return string|null
     */
    public function booleanFilter($value, $nc = false)
    {
        if (null === $value) {
            if ($nc) {
                return null;
            }

            return $this->translator->trans(
                'form.dontknow',
                [],
                'messages'
            );
        }
        if ($value) {
            return $this->translator->trans(
                'form.yes',
                [],
                'messages'
            );
        }

        return $this->translator->trans(
            'form.no',
            [],
            'messages'
        );
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function booleanIconeFilter($value)
    {
        if ($value) {
            return '<i class="green fas fa-check-circle"></i>';
        }

        return '<i class="red fas fa-minus-circle"></i>';
    }
}
