<?php

namespace App\DataFixtures\Bank;

use App\Entity\Bank\BankAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BankAccountFixtures extends Fixture implements DependentFixtureInterface
{
    /** @noinspection PhpParamsInspection */
    public function load(ObjectManager $manager)
    {
        $instance = new BankAccount();
        $instance->setAccountHolder('Danielle Robin');
        $instance->setIban('FR7620041010057277192458549');
        $instance->setBic('PSSTFRPPLIL');
        $instance->setBank(
            $this->getReference(BankFixtures::getReferenceName('LA BANQUE POSTALE'))
        );

        $manager->persist($instance);

        $this->setReference(self::getReferenceName($instance->getIban()), $instance);

        $ibans = [
            'FR3930003000701285191648T55',
            'FR5310096000503182951541B48',
            'FR1514508000507926544412G54',
            'FR6414508000709799515451W96',
            'FR5810096000408814726226S96',
        ];
        foreach ($ibans as $iban) {
            $instance = (new BankAccount())->setIban($iban);

            $manager->persist($instance);

            $this->setReference(self::getReferenceName($instance->getIban()), $instance);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BankFixtures::class,
        ];
    }

    public static function getReferenceName(string $key): string
    {
        return sprintf('%s-%s', BankAccount::class, $key);
    }
}
