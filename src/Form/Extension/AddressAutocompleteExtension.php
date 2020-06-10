<?php

namespace App\Form\Extension;

use App\Form\AddressType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class AddressAutocompleteExtension.
 */
class AddressAutocompleteExtension extends AbstractTypeExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [AddressType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $class = ['address-autocomplete'];
        if (isset($options['attr']['class'])) {
            $class[] = $options['attr']['class'];
        }
        $view->vars['attr']['class']        = implode(' ', $class);
    }
}
