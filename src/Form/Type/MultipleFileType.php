<?php

namespace App\Form\Type;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentInterface;
use App\Form\Document\EditDocumentNameType;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MultipleFileType.
 */
class MultipleFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'files',
                FileType::class,
                [
                    'mapped'   => false,
                    'label'    => false,
                    'attr'     => [
                        'placeholder' => 'files.drop',
                    ],
                    'multiple'    => true,
                    'required'    => false,
                    'constraints' => $options['file_constraints'],
                ]
            );
        if ($options['add_files_list']) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();

                    $entity = $form->getParent()->getData();
                    if ($entity) {
                        $class = new ReflectionClass($entity);
                        if ($class->implementsInterface(DocumentInterface::class)) {
                            $form
                                ->add('filesList',
                                    CollectionType::class,
                                    [
                                        'entry_type'    => EditDocumentNameType::class,
                                        'data'          => $entity->getDocuments(),
                                        'label'         => false,
                                        'mapped'        => false,
                                        'by_reference'  => false,
                                        'allow_delete'  => true,
                                        'block_prefix'  => 'files_list',
                                        'entry_options' => [
                                            'label'        => false,
                                            'block_prefix' => 'files_list_item'
                                        ],
                                    ]
                                )
                            ;
                        }
                    }
                }
            );
        }
        if (!$options['remove_form_submit']) {
            $builder->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    if ($form->getParent()->getData() instanceof DocumentInterface) {
                        /** @var DocumentInterface $entity */
                        $entity = $form->getParent()->getData();

                        if ($options['add_files_list']) {
                            /**
                             * @var ArrayCollection $filesList
                             */
                            $filesList = $form->get('filesList')->getData();
                            foreach ($entity->getDocuments() as $document) {
                                if (!$filesList->contains($document)) {
                                    $entity->removeDocument($document);
                                }
                            }
                        }

                        $files = $form->get('files')->getData();
                        if (!is_iterable($files)) {
                            return;
                        }

                        foreach ($files as $file) {
                            $document = new Document();
                            $document->setFile($file);
                            $entity->addDocument($document);
                        }
                    }
                }
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'auto_submit'        => false,
                'drag_and_drop'      => false,
                'add_files_list'     => true,
                'remove_form_submit' => false,
                'file_constraints'   => [],
            ]
        );
    }
}
