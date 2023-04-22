<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr-FR');

        for ($i=0; $i < 50; $i++) { 
            $image = new Image();
            $image->setName($faker->image(null, 480, 640));
            $product = $this->getReference('prod-'.rand(1,20));
            $image->setProducts($product);
            $manager->persist($image);
        }


        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            ProductFixtures::class
        ];
    }
}
