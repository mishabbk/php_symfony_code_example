<?php

namespace App\DataFixtures\Bank;

use App\DataFixtures\Country\CountryFixtures;
use App\Entity\Address;
use App\Entity\Bank\Bank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BankFixtures extends Fixture implements DependentFixtureInterface
{
    /** @noinspection PhpParamsInspection */
    public function load(ObjectManager $manager)
    {
        $instance = new Bank();
        $instance->setName('LA BANQUE POSTALE');
        $instance->setAddress(
            (new Address())
                ->setStreet('3 RUE PAUL DUEZ59900 CEDEX 9')
                ->setPostalCode(59000)
                ->setCity('LILLE')
                ->setCountry($this->getReference(CountryFixtures::getReferenceName('FR')))
        );
        $manager->persist($instance);

        $this->addReference(self::getReferenceName($instance->getName()), $instance);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CountryFixtures::class,
        ];
    }

    public static function getReferenceName(string $key): string
    {
        return sprintf('%s-%s', Bank::class, $key);
    }
}
