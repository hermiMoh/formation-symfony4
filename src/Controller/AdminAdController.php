<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
       
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page) ; 
       // $limit = 10 ;
       // $start = $page * $limit - $limit ;  
       // $total = count($repo->findAll()) ; 
       // $pages = ceil($total / $limit) ; // arrondi un nombre vers le plus grand 3.2 -> 4 
   
        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
          // 'ads' => $repo->findBy([], [], $limit, $start), 
          // 'pages' => $pages ,
          // 'page' => $page
        ]);
    }

      /**
     * Permet d'afficher le formulaire d'edition d'editer une annonce de cote d'admin
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request,EntityManagerInterface $em)  {
        $form = $this->createForm(AnnonceType::class, $ad) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted() && $form->isValid())  {
                $em->persist($ad) ;
                $em->flush() ;
                $this->addFlash(
                    'success', 
                    "Ad <strong>{$ad->getTitle()}</strong>  was edited !"
                ) ;
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' =>$ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de Supprimer Une Annonce 
     * @Route("/admin/ads/{id}/delete", name = "admin_ads_delete")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Ad $ad ,EntityManagerInterface $em)  {
            if (count($ad->getBookings()) > 0)  {
                $this->addFlash(
                    'warning' , 
                    "You can not delete this Ad <strong> {$ad->getTitle()} </strong> because was booked ! "  
                )  ;
            }   else {
            $em  ->remove($ad);
            $em  ->flush();

            $this->addFlash(
                'success' , 
                "The Ad <strong> {$ad->getTitle()} </strong> was deleted "  
            )  ;

        }
            return $this-> redirectToRoute('admin_ads_index');
    }
}
