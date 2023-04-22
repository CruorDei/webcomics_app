<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieFixtures extends Fixture
{
    private $counter = 1;
    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Manga', null, $manager);
        
        $this->createCategory('shonen', $parent, $manager);
        
        $parent2 = $this->createCategory('Manwha', null, $manager);

        $this->createCategory('Action', $parent2, $manager);

        $this->createCategory('Fantastique', $parent2, $manager);
        

        $manager->flush();
    }

    public function createCategory(string $name, Categorie $parent = null, ObjectManager $manager){

        $category = new Categorie();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
