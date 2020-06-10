<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateExtension.
 */
class DateExtension extends AbstractTypeExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [DateType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('timepicker', false);

        $resolver->setNormalizer('format', static function (Options $options) {
            if ($options['timepicker']) {
                return 'dd/MM/yyyy HH:mm';
            }

            return 'dd/MM/yyyy';
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $class = ['daterangepicker-input'];
        if (isset($options['attr']['class'])) {
            $class[] = $options['attr']['class'];
        }
        $view->vars['attr']['class']        = implode(' ', $class);

        if (isset($options['timepicker']) && $options['timepicker']) {
            $view->vars['attr']['data-timepicker'] = 'timepicker';
        }
    }
}
