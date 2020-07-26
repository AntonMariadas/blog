<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;




class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Créer 3 catégories fakées (boucles imbriquées)
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
            
            $manager->persist($category);
            
            //Créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();
                // .= veut dire on ajoute au contenu...
                $content = '<p>'. join($faker->paragraphs(5), '</p><p>') . '</^p>';
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedat($faker->dateTimeBetween('-6months'))
                        ->setCategory($category);
            
                $manager->persist($article);
                
                // On donne des commentaires a l'article
                for ($k = 1; $k <= mt_rand(4, 6); $k++) {
                    $comment = new Comment();
                    $content = '<p>'. join($faker->paragraphs(2), '</p><p>') . '</^p>';

                    // On fait en sorte que le commentaire ne soit pas plus récent que l'article
                    $now = new \DateTime();
                    // $interval = $now->diff($article->getCreatedAt());
                    // $days = $interval->days;
                    // $minimum = '-'. $days . ' days'; // -100 days

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($now)
                            ->setArticle($article);
                    
                $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
