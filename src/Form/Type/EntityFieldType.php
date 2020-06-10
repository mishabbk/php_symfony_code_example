<?php

namespace App\Form\Type;

use App\Form\DataTransformer\EntityFieldTransformer;
use App\Service\EntityIdentifierService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EntityFieldType extends AbstractType
{
    /** * @var UrlGeneratorInterface */
    private $router;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EntityIdentifierService */
    private $entityIdentifierService;

    public function __construct(
        UrlGeneratorInterface $router,
        EntityManagerInterface $entityManager,
        EntityIdentifierService $entityIdentifierService
    ) {
        $this->router                  = $router;
        $this->entityManager           = $entityManager;
        $this->entityIdentifierService = $entityIdentifierService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityFieldTransformer($this->entityManager, $this->entityIdentifierService);

        $builder->addViewTransformer(
            $transformer->setClassName($options['class'])
                        ->setMultiple($options['multiple'])
        );
        $builder->add(
            'class_entity',
            HiddenType::class
        );

        $formModifier = function (FormInterface $form, $choices = []) use ($options) {
            if (false === is_iterable($choices)) {
                throw new InvalidArgumentException('Argument choice need to be iterable');
            }

            $form->add(
                'class_choices',
                EntityRemoteType::class,
                [
                    'mapped'            => false,
                    'label'             => false,
                    'class'             => $options['class'],
                    'multiple'          => $options['multiple'],
                    'route'             => $this->router->generate(
                        $options['route'],
                        [],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    'custom_filters'    => $options['custom_filters'],
                    'pagination'        => true,
                    'validation_groups' => false,
                    'choices'           => $choices,
                    'data'              => $options['multiple'] ? $choices : null,
                    'placeholder'       => $options['placeholder'],
                ]
            );
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier, $options) {
                $formModifier(
                    $event->getForm(),
                    $event->getData() ? ($options['multiple'] ? $event->getData() : [$event->getData()]) : []
                );
            }
        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier, $transformer, $options) {
                if (empty($event->getData()['class_choices'])) {
                    return;
                }
                $data   = $event->getData()['class_choices'];
                $entity = $transformer->findEntities($data);
                $formModifier($event->getForm(), $options['multiple'] ? $entity : [$entity]);
            }
        );
    }

    public function getParent(): string
    {
        return ChoiceRemoteType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['class', 'route']);
        $resolver->setDefaults(
            [
                'multiple'       => false,
                'placeholder'    => null,
                'custom_filters' => [],
            ]
        );
    }
}
