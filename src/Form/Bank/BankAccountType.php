<?php

namespace App\Form\Bank;

use App\Entity\Bank\BankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'iban',
                TextType::class,
                [
                    'label' => 'bank.account.iban',
                    'attr'  => [
                        'placeholder' => 'bank.account.iban',
                    ],
                ]
            )
            ->add(
                'bic',
                TextType::class,
                [
                    'label'    => 'bank.account.bic',
                    'required' => false,
                    'attr'     => [
                        'placeholder' => 'bank.account.bic',
                    ],
                ]
            )
            ->add(
                'accountHolder',
                TextType::class,
                [
                    'label'    => 'bank.account.account_holder',
                    'required' => false,
                    'attr'     => [
                        'placeholder' => 'bank.account.account_holder',
                    ],
                ]
            )
            ->add(
                'openingDate',
                DateType::class,
                [
                    'label'    => 'bank.account.opening_date',
                    'widget'   => 'single_text',
                    'required' => false,
                    'html5'    => false,
                    'attr'     => [
                        'placeholder' => 'bank.account.opening_date',
                    ],
                ]
            )
            ->add(
                'bank',
                BankFieldType::class,
                [
                    'label' => 'bank.account.bank',
                ]
            )
        ;
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                /**
                 * @var BankAccount $bankAccount
                 */
                $bankAccount = $form->getData();
                $bankAccount->setIban(preg_replace('/[^A-Z0-9]/', '', $form->get('iban')->getData()));
                $bankAccount->setBic(preg_replace('/[^A-Z0-9]/', '', $form->get('bic')->getData()));
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => BankAccount::class,
            ]
        );
    }
}
