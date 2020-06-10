<?php

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ButtonExtension.
 */
class ButtonExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ButtonExtension constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param $var
     *
     * @return string
     */
    private function getText($var)
    {
        return $this->translator->trans($var);
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'button',
                [$this, 'getButton'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'button_dropdown',
                [$this, 'getButtonDropdown'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'button_action',
                [$this, 'getButtonAction'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param string $route
     * @param string $label
     * @param string $class
     * @param array  $options
     *
     * @return string
     */
    public function getButton($route, $label, $class, $options = [])
    {
        $attr = null;
        if (!empty($options['attr'])) {
            foreach ($options['attr'] as $key => $value) {
                $attr .= ' '.$key.'="'.$value.'"';
            }
        }
        if (!array_key_exists('size', $options)) {
            $size = 'btn-sm';
        } else {
            $size = $options['size'];
        }

        return '<a href="'.$route.'" class="btn '.$size.' '.$class.' '.($options['class'] ?? 'btn-block').'" '.
               (empty($options['target']) ? null : 'target="'.$options['target'].'"').
               $attr.'>'.
               $this->getText($label).
               '</a>';
    }

    /**
     * @param string      $color
     * @param array       $mainElem
     * @param array       $subElems
     * @param string|null $direction
     *
     * @return string
     */
    public function getButtonDropdown(string $color, array $mainElem, array $subElems = [], ?string $direction = null)
    {
        $subMenu = '';
        foreach ($subElems as $subElem) {
            if (!is_array($subElem) || empty($subElem)) {
                continue;
            }
            $class      = null;
            $attributes = '';
            if (!empty($subElem[2]) && is_array($subElem[2])) {
                foreach ($subElem[2] as $key => $value) {
                    if ('class' === $key) {
                        $class = $value;
                    } else {
                        $attributes .= $key.'="'.$value.'" ';
                    }
                }
            }
            $subMenu .= '<a class="dropdown-item '.$class.'" href="'.$subElem[0].'" '.$attributes.'>'.$subElem[1].'</a>';
        }

        $attributes = '';
        if (!empty($mainElem[2]) && is_array($mainElem[2])) {
            foreach ($mainElem[2] as $key => $value) {
                $attributes .= $key.'="'.$value.'" ';
            }
        }

        if (empty($subMenu) && $mainElem) {
            return '<a href="'.$mainElem[0].'" class="btn btn-sm '.$color.'" '.$attributes.'>'.$mainElem[1].'</a>';
        }

        if($mainElem && 1 === count($mainElem)){
            return '<div class="btn-group '.$direction.'">'.
                   '<button class="btn '.$color.' '.(preg_match('/btn-block/', $color) ? ' btn-block' : null).' dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                   $mainElem[0].
                   '</button>'.
                   '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.
                   $subMenu.
                   '</div>'.
                   '</div>';
        }

        return '<div class="btn-group'.(preg_match('/btn-block/', $color) ? ' btn-block' : null).'">'.
               '<div class="btn-group '.($mainElem ? 'dropleft' : null).'">'.
               '<button type="button" class="btn btn-sm '.$color.' dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>'.
               '<div class="dropdown-menu '.$direction.'">'.$subMenu.'</div>'.
               '</div>'.
               ($mainElem ? '<a href="'.$mainElem[0].'" class="btn btn-sm '.$color.'" '.$attributes.'>'.$mainElem[1].'</a>' : null).
               '</div>';
    }

    /**
     * @param array $subElems
     *
     * @return string
     */
    public function getButtonAction(array $subElems){
        return $this->getButtonDropdown('btn btn-default', [], $subElems, 'dropdown-menu-right');
    }
}
