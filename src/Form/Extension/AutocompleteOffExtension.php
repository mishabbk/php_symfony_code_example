<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class AutocompleteOffExtension.
 */
class AutocompleteOffExtension extends AbstractTypeExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [TextType::class, DateType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['autocomplete'] = 'Off';
    }
}
