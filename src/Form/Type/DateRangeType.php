<?php

namespace App\Form\Type;

use App\Form\DataTransformer\DateRangeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateRangeType.
 */
class DateRangeType extends AbstractType
{
    /**
     * @var DateRangeTransformer
     */
    private $transformer;

    /**
     * DateRangeType constructor.
     */
    public function __construct(DateRangeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->transformer);
        $builder->add(
            'range',
            TextType::class,
            [
                'label' => $options['label'],
                'attr'  => [
                    'placeholder' => $options['label'] ?: 'form.date_range',
                    'class'       => 'daterange',
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'daterange';
    }
}
