<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Service\ScoringService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $counter = $i % 4;
            $edCounter = $i % 3;
            $pdCounter = $i % 2;
            $customer = new Customer();
            $customer->setName('Name'.$i);
            $customer->setSurname('Surname'.$i);
            $customer->setEmail('email'.$i.'@'.(0 == $counter ? 'gmail.com' : (1 == $counter ? 'yandex.ru' : (2 == $counter ? 'mail.ru' : 'yahoo.com'))));
            $customer->setPhone('+7('.(0 == $counter ? '900' : (1 == $counter ? '901' : (2 == $counter ? '902' : '999'))).')000-00-00');
            $customer->setEducation(0 == $edCounter ? Customer\EducationType::HIGHER : (1 == $edCounter ? Customer\EducationType::SPECIAL : Customer\EducationType::SECONDARY));
            $customer->setPdProcessingPermission(0 == $pdCounter);
            $customer->setScore(ScoringService::makeScoring($customer->getPhone(), $customer->getEmail(), $customer->getEducation(), $customer->pdProcessingPermission()));
            $manager->persist($customer);
        }
        $manager->flush();
    }
}
