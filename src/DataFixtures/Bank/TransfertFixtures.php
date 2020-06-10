<?php

namespace App\DataFixtures\Bank;

use App\Entity\Bank\Transfert\Transfert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Fixtures for Transfert Entity
 *      Ref - by 'reference' on instance
 *
 * Class TransfertFixtures
 * @package App\DataFixtures\Bank
 */
class TransfertFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $values = [
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR7620041010057277192458549')),
                'movement'     => Transfert::MOVEMENT_IN,
                'date'         => (new \DateTime())->modify('-14 days'),
                'amount'       => 15000,
                'reference'    => 'Transfert Reference 1',
                'comment'      => 'Transfert comment 1',
                'invoices'     => [],
            ],
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR7620041010057277192458549')),
                'movement'     => Transfert::MOVEMENT_IN,
                'date'         => (new \DateTime())->modify('-6 days'),
                'amount'       => 115000,
                'reference'    => 'Transfert Reference 2',
                'comment'      => 'Transfert comment 2',
                'invoices'     => [],
            ],
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR3930003000701285191648T55')),
                'movement'     => Transfert::MOVEMENT_IN,
                'date'         => (new \DateTime())->modify('-30 days'),
                'amount'       => 15000,
                'reference'    => 'Transfert Reference 3',
                'comment'      => 'Transfert comment 3',
                'invoices'     => [],
            ],
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR7620041010057277192458549')),
                'movement'     => Transfert::MOVEMENT_OUT,
                'date'         => (new \DateTime())->modify('-5 days'),
                'amount'       => 10000,
                'reference'    => 'Transfert Reference 4',
                'comment'      => 'Transfert comment 4',
                'invoices'     => [],
            ],
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR5810096000408814726226S96')),
                'movement'     => Transfert::MOVEMENT_IN,
                'date'         => (new \DateTime())->modify('-46 days'),
                'amount'       => 230000,
                'reference'    => 'Transfert Reference 5',
                'comment'      => 'Transfert comment 5',
                'invoices'     => [],
            ],
            [
                'bank_account' => $this->getReference(BankAccountFixtures::getReferenceName('FR5810096000408814726226S96')),
                'movement'     => Transfert::MOVEMENT_OUT,
                'date'         => (new \DateTime())->modify('-6 days'),
                'amount'       => 10500,
                'reference'    => 'Transfert Reference 6',
                'comment'      => 'Transfert comment 6',
                'invoices'     => [],
            ],
        ];

        foreach ($values as $value) {
            $instance = (new Transfert())
                ->setBankAccount($value['bank_account'])
                ->setMovement($value['movement'])
                ->setDate($value['date'])
                ->setAmount($value['amount'])
                ->setReference($value['reference'])
                ->setComment($value['comment']);

            $manager->persist($instance);

            $this->addReference(self::getReferenceName($instance->getReference()), $instance);
        }

        $manager->flush();
    }

    /**
     * Fixture dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            BankAccountFixtures::class,
        ];
    }

    /**
     * Get reference name of instance
     *
     * @param string $key
     * @return string
     */
    public static function getReferenceName(string $key): string
    {
        return sprintf('%s-%s', Transfert::class, $key);
    }
}
