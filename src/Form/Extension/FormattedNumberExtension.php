<?php

namespace App\Form\Extension;

use App\Form\Type\FormattedNumberType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class FormattedNumberExtension.
 */
class FormattedNumberExtension extends AbstractTypeExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormattedNumberType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $class = ['format-number'];
        if (isset($options['attr']['class'])) {
            $class[] = $options['attr']['class'];
        }
        $view->vars['attr']['class']        = implode(' ', $class);
    }
}
