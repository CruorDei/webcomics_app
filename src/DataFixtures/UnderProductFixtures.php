<?php

namespace App\DataFixtures;

use App\Entity\UnderProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UnderProductFixtures extends Fixture
{

    public function __construct(
        private SluggerInterface $slugger
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 20; $i++) { 
            $this->createUnder($manager);
        }
    }

    public function createUnder(ObjectManager $manager){
        $faker = Faker\Factory::create('fr-FR');
        
            $product = new UnderProduct();
            $product->setNum('Chap' . $faker->numberBetween(1,100));
            $product->setSlug($this->slugger->slug($product->getNum())->lower());
            $parentProduct = $this->getReference('prod-'. rand(1,20));
            $product->setParentProduct($parentProduct);
            
            $manager->persist($product);
        

        $manager->flush();

        return $product;
    }


}
