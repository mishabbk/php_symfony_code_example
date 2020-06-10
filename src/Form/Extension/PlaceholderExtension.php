<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class PlaceholderExtension extends AbstractTypeExtension
{
    /**
     * @return array|iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            DateType::class,
            IntegerType::class,
            NumberType::class,
            TextType::class,
            TextareaType::class,
        ];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('label', $view->vars) && $view->vars['label']) {
            if (!array_key_exists('attr', $view->vars)) {
                $view->vars['attr'] = [];
            }
            if (!array_key_exists('placeholder', $view->vars['attr'])) {
                $view->vars['attr']['placeholder'] = $view->vars['label'];
            }
        }
    }
}
