<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $em)
    {
        $booking = new Booking() ;
        $form = $this->createForm(BookingType::class, $booking) ; 

        $form->handleRequest($request) ;   // demande de formulaire la requete passer

        if ($form->isSubmitted() && $form->isValid())  {

            $user = $this->getUser() ; 

            $booking->setBooker($user)
                    ->setAd($ad)  ;

                    // Si les dates ne sont pas disponibles Message d'error 
                        if(!$booking->isBookableDates())  {
                            $this->addFlash(
                                'warning',
                                "selected dates not available ! Choose other date PLZ !"
                            );
                        }   else {
                             // si non enregistrement et redirection 
                            $em->persist($booking) ;
                            $em->flush()  ;
                
                            return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 
                                'withAlert' => true 
                            ])  ;
                        }
         }
                   
           
        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une reservation 
     *@Route("/booking/{id}" , name = "booking_show")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function show(Booking $booking, Request $request, EntityManagerInterface $em)  {

        $comment = new Comment()  ;

        $form = $this-> createForm(CommentType::class, $comment) ;

        $form->handleRequest($request)  ;

        if ($form->isSubmitted() && $form->isValid())  {

            $comment->setAd($booking->getAd())   // lier le commentaire a une annonce
                    ->setAuthor($this->getUser())  ;  // lier aussi le commentaire a l'utilisateur connecter 

            $em->persist($comment)  ;
            $em->flush()  ;

            $this->addFlash(
                'success', 
                "your comment has been taken into account"
            ) ; 
        }


            return $this->render('booking/show.html.twig', [
                'booking' => $booking,
                'form'    => $form->createView()
            ]) ;
    }


}
