<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Priority;

class PriorityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $priority = new Priority();
        $priority->setPriorityName('High Priority');
        

    	$manager->persist($priority);
        $manager->flush();
    }
}
