<?php

namespace App\Form\Type;

use App\Form\DataTransformer\FormattedNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormattedNumberType.
 */
class FormattedNumberType extends AbstractType
{
    /** @var FormattedNumberTransformer */
    private $transformer;

    public function __construct(FormattedNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->transformer);
    }

    public function getParent(): string
    {
        return NumberType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'scale' => 2,
            ]
        );
    }
}
