<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
Use App\Entity\Page;
                Use App\Entity\Categorie;

class PageFixtures extends Fixture
{               
    public function load(ObjectManager $manager)
{
// $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create();
    for($i=1;$i<6;$i++)
    {
        $categorie =new Categorie();
        $categorie->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true));
        $manager->persist($categorie);
        for($j=1;$j<20;$j++)
         {
          $page =new Page();
        $page->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true))
        ->setAuteur($faker->name())
        ->setCreatedAt($faker->dateTimeBetween($startDate = '-3days', $endDate = 'now', $timezone = null))
        ->setJourAt(new \DateTime())
        ->setContenu($faker->paragraph($nbSentences = 5, $variableNbSentences = true))
        ->setCategorie($categorie);
        $manager->persist($page);
        }
    }
        $manager->flush();
    }
}
