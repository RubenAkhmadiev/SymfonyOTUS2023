<?php

namespace App\DataFixtures\Backoffice;

use App\Backoffice\Entity\Partner;
use App\Backoffice\Enum\PartnerTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PartnerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $partner = new Partner();
            $partner->setName(name: 'partner_' . $i);
            $partner->setType(PartnerTypeEnum::RESTAURANT);
            $manager->persist($partner);
        }

        $manager->flush();
    }
}
