<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FileType.
 */
class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'file',
            BaseFileType::class,
            [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'files.drop',
                ],
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'file_type';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'auto_submit'   => false,
                'drag_and_drop' => false,
            ]
        );
    }
}
