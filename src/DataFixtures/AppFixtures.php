<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        for($i = 0; $i <= 30; $i++) {
            $offer = new Offer();

            $title = $faker->sentence(2);
            $description = $faker->paragraph(6);

            $offer->setTitle($title)
            ->setDescription($description);
            
            $manager->persist($offer);
        }   
           $manager->flush();
     }   
}
