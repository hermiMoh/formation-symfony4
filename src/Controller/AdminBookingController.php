<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher tous les reservation pour l'admin
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
       
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page) ; 
                   
        return $this->render('admin/booking/index.html.twig', [
           'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'editer une reservation 
     * 
     * @Route("/admin/bookings/{id}/edit", name = "admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $em)  {
        $form = $this->createForm(AdminBookingType::class, $booking) ;
        $form->handleRequest($request) ;

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setAmount(0);
            
            $em->persist($booking);
            $em->flush() ;

            $this->addFlash(
                'success' ,
                "Booking N:  <strong>{$booking->getId()} was edited with Succes "
            );

            return $this->redirectToRoute('admin_booking_index')  ;
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView() ,
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une reservation
     * 
     * @Route("/admin/bookings/{id}/delete", name = "admin_booking_delete")
     * 
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $em)  {
            $em->remove($booking)  ;
            $em->flush() ;

            $this->addFlash(
                'success',
                "Booking  was DELETED with Success ! "
            );

           return  $this->redirectToRoute("admin_booking_index") ;
    }
}
