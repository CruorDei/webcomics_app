<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ProductFixtures extends Fixture
{
    private $counter = 1;
    public function __construct(
        private SluggerInterface $slugger
    )
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr-FR');

        for ($i=0; $i <= 20; $i++) { 
            $product = new Product();
            $product->setName($faker->text(10));
            $product->setDescription($faker->text());
            $product->setSlug($this->slugger->slug($product->getName())->lower());

            $category = $this->getReference('cat-'. rand(1,4));
            $product->setCategories($category);

            $this->setReference('prod-'.$i, $product);

            $manager->persist($product);
            $this->addReference('prod-'.$this->counter, $product);
            $this->counter++;
        }


        $manager->flush();
    }
}
