<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder ;
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder ;
    }
    public function load(ObjectManager $manager)
    {

         $faker = Factory::create('en_US')  ;
              // add users with fixtures 

              $users = []  ;
              $genres = ['male', 'female'] ;

           

               


              for ($i=1; $i<=10; $i++)  {
                    $user = new User() ;

                    $genre = $faker->randomElement($genres)  ;  // avoir un genre d'une maniere alieatoire

                     
                    $picture = 'https://randomuser.me/api/portraits/'  ;
                    $pictureId = $faker->numberBetween(1, 99) . '.jpg' ;

                    $hash = $this->encoder->encodePassword($user, 'password')  ;
                    
                    $picture .=  ($genre == 'male' ? 'men/' : 'women/') .$pictureId ; //condition ternaire
                    $user ->setFirstName($faker->firstname($genre))
                          ->setLastName($faker->lastname)
                          ->setEmail($faker->email)
                          ->setIntroduction($faker->sentence)
                          ->setDescription('<p>' .  join('</p><P>',$faker->paragraphs(5)) .'</p>')
                          ->setHash($hash) 
                          ->setPicture($picture);

                          $manager ->persist($user)  ;

                          $users[]  = $user ;
              }

         // Nous gerons les annonces Ads .
         for ($i=1; $i<=30; $i++)  {
            $ad = new Ad() ;

            $title = $faker->sentence();

           
           // $coverImage = $faker->imageUrl(1000,350);
              $coverImage = "https://picsum.photos/id/".mt_rand(1,30)."/1000/350" ;
            $introduction = $faker->paragraph(2);
            $content = '<p>' .  join('</p><P>',$faker->paragraphs(5)) .'</p>';

                $user = $users[mt_rand(0, count($users) - 1)]  ;

            $ad->setTitle($title) 
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(40, 200))
            ->setRooms(mt_rand(1, 5))  
            ->setAuthor($user);

            for ($j=1 ; $j <= mt_rand(2,5); $j++) {
                $image = new Image() ;

                $image ->setUrl("https://picsum.photos/id/".mt_rand(1,30)."/600/250")
                       ->setCaption($faker->sentence())
                       ->setAd($ad) ;
                       $manager->persist($image) ;
            }

            $manager->persist($ad);
        }
        

        $manager->flush();
    }
}
