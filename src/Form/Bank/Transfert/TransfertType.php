<?php

namespace App\Form\Bank\Transfert;

use App\Entity\Bank\Transfert\Transfert;
use App\Entity\Property\Task\Invoice;
use App\Form\Bank\BankAccountFieldType;
use App\Form\ChoiceGroupType;
use App\Form\Type\FormattedNumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'bankAccount',
                BankAccountFieldType::class,
                [
                    'label' => 'bank.account',
                ]
            )
            ->add(
                'movement',
                ChoiceGroupType::class,
                [
                    'label'                           => 'bank.transfert.movement',
                    'choices'                         => [
                        'bank.transfert.movement.in'  => Transfert::MOVEMENT_IN,
                        'bank.transfert.movement.out' => Transfert::MOVEMENT_OUT,
                    ],
                ]
            )
            ->add(
                'date',
                DateType::class,
                [
                    'label'           => 'bank.transfert.date',
                    'widget'          => 'single_text',
                    'html5'           => false,
                    'attr'            => [
                        'placeholder' => 'bank.transfert.date',
                    ],
                ]
            )
            ->add(
                'amount',
                FormattedNumberType::class,
                [
                    'label'           => 'bank.transfert.amount',
                    'attr'            => [
                        'placeholder' => 'bank.transfert.amount',
                    ],
                ]
            )
            ->add(
                'reference',
                TextType::class,
                [
                    'label'           => 'bank.transfert.reference',
                    'attr'            => [
                        'placeholder' => 'bank.transfert.reference',
                    ],
                ]
            )
            ->add(
                'comment',
                TextareaType::class,
                [
                    'label'    => 'bank.transfert.comment',
                    'required' => false,
                    'attr'     => [
                        'placeholder' => 'bank.transfert.comment',
                        'rows'        => 5,
                    ],
                ]
            )
            ->add(
                'invoices',
                EntityType::class,
                [
                    'class'    => Invoice::class,
                    'label'    => 'bank.transfert.invoices',
                    'multiple' => true,
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Transfert::class,
            ]
        );
    }
}
