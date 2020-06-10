<?php

namespace App\Form\Document;

use App\Entity\Document\TypeToEntity;
use App\Handler\Document\DocumentHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TypeToEntityType.
 */
class TypeToEntityType extends AbstractType
{
    /** @var DocumentHandler */
    private $documentHandler;

    public function __construct(DocumentHandler $documentHandler)
    {
        $this->documentHandler = $documentHandler;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'entity',
                ChoiceType::class,
                [
                    'label'   => 'typeToEntity.entity',
                    'choices' => $this->documentHandler->getTypableEntities(),
                ]
            )
            ->add(
                'required',
                CheckboxType::class,
                [
                    'label'    => 'typeToEntity.required',
                    'required' => false,
                ]
            )
            ->add(
                'requirement',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'document.type.requirement',
                    'attr'     => [
                        'placeholder' => 'document.type.requirement',
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label'    => 'document.type.description',
                    'attr'     => [
                        'placeholder' => 'document.type.description',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => TypeToEntity::class,
            ]
        );
    }
}
