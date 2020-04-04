<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {

       // $repo= $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll() ;
        return $this->render('ad/index.html.twig', [
            'ads' => $ads 
        ]);
    }


    /**
    * function to create an AD
    *@Route("/ads/new", name ="ads_create")
    *@IsGranted("ROLE_USER")
    * @return Response
    */
    public function create(Request $request, EntityManagerInterface $entityManager)  {

          $ad = new Ad() ;

          $form = $this->createForm(AnnonceType::class, $ad);

          $form->handleRequest($request);

        

            if ($form->isSubmitted() && $form->isValid())  {
                foreach ($ad->getImages() as $image )  {
                        $image->setAd($ad)  ;
                        $entityManager->persist($image)  ;
                }

                $ad->setAuthor($this->getUser())  ;

                $entityManager->persist($ad);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    "The Ad  <strong>{$ad->getTitle()}</strong> saved with Success !"
                );

                return $this->redirectToRoute('ads_show', [
                    'slug' => $ad->getSlug() 
                ]);
            }

     
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ])  ;
      }

      /**
       * display a form to EDIT A SELECTED AD
       * @Route("/ads/{slug}/edit", name ="ads_edit")
       * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message = "This Ad not yours, you can not edit it !")
       * @return Response
       */
      public function edit(Ad $ad, Request $request, EntityManagerInterface $entityManager)  {

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())  {
            foreach ($ad->getImages() as $image )  {
                    $image->setAd($ad)  ;
                    $entityManager->persist($image)  ;
            }

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "The  Ad  <strong>{$ad->getTitle()}</strong> Was updated with Success !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug() 
            ]);
        }

       return  $this->render('ad/edit.html.twig', [
           'form' => $form->createView(),
           'ad' => $ad
       ]) ;
      }

    /**
     * Display one Ad
     *@Route("/ads/{slug}", name = "ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad) 
    {
        // i get ad with selected slug ..
     //   $ad = $repo->findOneBySlug($slug) ;

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]) ;
    }

    /**
     * permet de supprimer une annonce d'un utilisateur 
     * @Route("/ads/{slug}/delete", name = "ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message ="You can not access for this resource !")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $entityManager)  {
           $entityManager->remove($ad) ;
           $entityManager->flush() ;


           $this->addFlash(
            'success',
            "The  Ad  <strong>{$ad->getTitle()}</strong> Was deleted with Success !"
        );

         
        return   $this->redirectToRoute("ads_index")  ;
    }

}
