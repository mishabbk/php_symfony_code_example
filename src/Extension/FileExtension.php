<?php

namespace App\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FileExtension.
 */
class FileExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [FileType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['drag_and_drop', 'auto_submit']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $class = [];

        if (isset($options['drag_and_drop']) && true === $options['drag_and_drop']) {
            $class[] = 'drag-and-drop';
        }

        if (isset($options['auto_submit']) && true === $options['auto_submit']) {
            $class[] = 'submit-on-upload';
        }

        if (!empty($class)) {
            if (isset($options['attr']['class'])) {
                $class[] = $options['attr']['class'];
            }

            $view->vars['attr']['class'] = implode(' ', $class);
        }
    }
}
