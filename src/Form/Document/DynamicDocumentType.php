<?php

namespace App\Form\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentTypeInterface;
use App\Form\Type\DragAndDropMultipleFileType;
use App\Repository\Document\DocumentTypeRepository;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class DynamicDocumentType extends AbstractType
{
    /** @var DocumentTypeRepository */
    private $repository;

    /**
     * @var Security */
    private $security;

    public function __construct(
        DocumentTypeRepository $repository,
        Security $security
    ) {
        $this->repository = $repository;
        $this->security   = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($options['class'])) {
            throw new MissingOptionsException('The required option "class" is missing');
        }
        $class = new ReflectionClass($options['class']);
        if (!$class->implementsInterface(DocumentTypeInterface::class)) {
            throw new InvalidOptionsException(sprintf('%s should implements %s', $class->getName(), DocumentTypeInterface::class));
        }

        foreach ($this->repository->getAllForEntity(
            call_user_func(
                [
                    $class->getName(),
                    'getEntityName',
                ]
            )
        ) as $type) {
            $builder->add(
                sprintf('documents-%d', $type->getId()),
                DragAndDropMultipleFileType::class,
                [
                    'label'  => $type,
                    'mapped' => false,
                ]
            );
            $builder->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) use ($type) {
                    $form = $event->getForm();

                    $files = $form->get(sprintf('documents-%d', $type->getId()))->getData();
                    if (!is_iterable($files)) {
                        return;
                    }
                    if (!is_iterable($files['files'])) {
                        return;
                    }

                    /** @var DocumentTypeInterface $entity */
                    $entity = $form->getParent()->getData();

                    foreach ($files['files'] as $file) {
                        $document = new Document();
                        $document->setFile($file);
                        $document->setType($type);
                        $entity->addDocument($document);
                    }
                }
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => null,
            ]
        );
    }
}
