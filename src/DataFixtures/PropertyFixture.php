<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0 ; $i<100 ; $i++){
            $faker = Factory::create('fr_FR');
            $property = new Property();
            $property->setTitle($faker->words(3,true))
                     ->setDescription($faker->sentences(3,true))
                     ->setSurface($faker->numberBetween(20,350))
                     ->setRooms($faker->numberBetween(2,10))
                     ->setBadrooms($faker->numberBetween(2,4))
                    ->setFloor($faker->numberBetween(0,15))
                    ->setPrice($faker->numberBetween(10000,300000))
                    ->setAddress($faker->address)
                    ->setCity($faker->city)
                    ->setPostalCode($faker->postcode)
                    ->setSold(false);
            $manager->persist($property);


        }
        // $product = new Product();
        $manager->flush();
    }
}
