<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
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
        
         $adminRole = new Role()  ;
         $adminRole->setTitle('ROLE_ADMIN')  ;

         $manager->persist($adminRole)  ;
            
         $adminUser = new User()  ;
         $adminUser->setFirstName('Mohamed')
                   ->setLastName('Hermi')
                   ->setEmail('med.hermi@gmail.com')
                   ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                   ->setPicture('https://randomuser.me/api/portraits/men/25.jpg')
                   ->setIntroduction($faker->sentence)
                   ->setDescription('<p>' .  join('</p><P>',$faker->paragraphs(3)) .'</p>') 
                   ->addUserRole($adminRole) ;
         $manager->persist($adminUser)  ;

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

            // GESTION DE RESERVATIONS 
            for ($j=1 ; $j <= mt_rand(0,10) ; $j++)  {

                $booking = new Booking () ;

                $createdAt = $faker->dateTimeBetween('-6 months') ;

                $startDate = $faker->dateTimeBetween('-3 months') ;

                // Gestion de la Date de fin 

                $duration = mt_rand(3 , 10) ;

                $endDate = (clone $startDate)->modify("+$duration days") ;

                $amount = $ad->getPrice() * $duration  ;

                $booker = $users[mt_rand(0, count($users) - 1 )] ;

                $comment = $faker->paragraph()  ;

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment)  ;
                        $manager->persist($booking)  ;

                        // Gestion des commentaires 
                        if (mt_rand(0, 1)) {
                            $comment = new Comment()  ;
                            $comment->setContent($faker->paragraph())
                                    ->setRating(mt_rand(1, 5))
                                    ->setAuthor($booker)
                                    ->setAd($ad)  ;
                             $manager->persist($comment)  ;
                                    
                        }
                
            }

            $manager->persist($ad);
        }
        

        $manager->flush();
    }
}
