<?php

namespace App\Form\Bank;

use App\Entity\Bank\BankAccount;
use App\Entity\Bank\BankAccountInterface;
use App\Repository\Bank\BankAccountRepository;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use UnexpectedValueException;

class BankAccountIbanBicType extends AbstractType
{
    /** @var BankAccountRepository */
    private $bankAccountRepository;

    /**
     * BankAccountIbanBicType constructor.
     */
    public function __construct(BankAccountRepository $bankAccountRepository)
    {
        $this->bankAccountRepository = $bankAccountRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();

                $entity = $form->getParent()->getData();
                if(!$entity){
                    throw new UnexpectedValueException('Parent data is null');
                }

                $class = new ReflectionClass($entity);
                if (!$class->implementsInterface(BankAccountInterface::class)) {
                    throw new InvalidOptionsException(sprintf('%s should implements %s', $class->getName(), BankAccountInterface::class));
                }

                /** @var BankAccount $bankAccount */
                $bankAccount = $entity->getBankAccount();
                $form
                    ->add(
                        'iban',
                        TextType::class,
                        [
                            'label' => 'bank.account.iban',
                            'attr'  => [
                                'placeholder' => 'bank.account.iban',
                            ],
                            'data'  => $bankAccount ? $bankAccount->getIban() : null,
                        ]
                    )
                    ->add(
                        'bic',
                        TextType::class,
                        [
                            'label' => 'bank.account.bic',
                            'attr'  => [
                                'placeholder' => 'bank.account.bic',
                            ],
                            'data'  => $bankAccount ? $bankAccount->getBic() : null,
                        ]
                    );
            }
        );
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                /** @var BankAccountInterface $entity */
                $entity = $form->getParent()->getData();

                $iban = $form->get('iban')->getData();
                $iban = preg_replace('/[^A-Z0-9]/', '', $iban);
                if ($iban) {
                    $bankAccount = $this->bankAccountRepository->findOneBy(['iban' => $iban]);
                    if (!$bankAccount instanceof BankAccount) {
                        $bankAccount = (new BankAccount())->setIban($iban)
                                                          ->setBic(
                                                              preg_replace('/[^A-Z0-9]/', '', $form->get('bic')->getData())
                                                          );
                    }
                    $entity->setBankAccount($bankAccount);
                } else {
                    $entity->setBankAccount(null);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'  => BankAccount::class,
                'required'    => false,
                'mapped'      => false,
                'constraints' => [
                    new Valid(),
                ],
                'empty_data'  => null,
            ]
        );
    }
}
