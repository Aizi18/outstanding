<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('FR_fr');
        //Créer 3 catégories fakées
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category(); //ajouter le use en haut
            $category->setTitle($faker->sentence()) //------>voir faker php sur la doc la liste des possibilités
                ->setDescription($faker->paragraph()); //nombres de paragraph par defaut 3 dans la librairie si nn on  peut mettre un nombre
            $manager->persist($category);
            //CRÉER entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4, 6); $j++) { //mt-rand(4, 6) c'est une fontction phppour donner un nombre random haut hazard
                $article = new Article();

                // on a fait cette manip car paragraphs est pas comme pragraph et ca me renvoi un tableau
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') .
                    '<p>'; //je vais avoir un debut de p un paragr.. et un fin de p et un debut de p
                // et la cloture de mes 5 parag

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months')) //date entre d'ici 6 mois à ce jour
                    ->setCategory($category); //on place cet article dans la category qu'on a créer
                $manager->persist($article);
            }
        }
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();

            $article->setTitle("Titre de l'article n°$i")
                ->setContent("<p>Contenu de l'article n° $i</p>")
                ->setImage("http://placehold.it/350x150")
                ->setCreatedAt(new \DateTime());
            $manager->persist($article);
        }
        for ($k = 1; $k <= mt_rand(4, 10); $k++) {
            $comment = new Comment();

            $comment->setAuthor()
                ->setContent("<p>Contenu de l'article n° $i</p>")
                ->setImage()
                ->setCreatedAt();
            $manager->persist($article);
        }
        $manager->flush();
    }
}
