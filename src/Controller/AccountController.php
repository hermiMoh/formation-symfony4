<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et gere le formulaire de Login !
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {

        $error = $utils->getLastAuthenticationError() ;
        $username = $utils ->getLastUsername() ;
       
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

     /**
      * permet de deconnecter le user 
      *@Route("/logout", name="account_logout")
      * @return void
      */
    public function logout() {

    }

    /**
     * afficher le formulaire d'inscription 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder) {
         $user  = new User() ;
         $form = $this->createForm(RegistrationType::class, $user)  ;

         $form ->handleRequest($request)  ;

         if ($form->isSubmitted() && $form->isValid())  {
            $hash = $encoder->encodePassword($user, $user->getHash())  ;
            $user->setHash($hash)  ;

             $entityManager->persist($user)  ;
             $entityManager->flush()  ;

             $this->addFlash(
                 'success'  , "Your Account was created ! you can login Now !"
             ) ;
             return $this->redirectToRoute('account_login');
         }

 
        return $this->render('account/registration.html.twig', [
            'form'  => $form->createView()
        ])  ;
    }

    /**
     * Afficher et traiter le formulaire pour Editer user Profile
     *
     * @Route("/account/profile", name= "account_profile")
     * @return Response
     */
    public function profile(Request $request , EntityManagerInterface $entityManager)  {

        $user = $this->getUser()  ;

        $form = $this->createForm(AccountType::class, $user)  ;
         
        $form -> handleRequest($request)  ;
        if ($form->isSubmitted() && $form->isValid())  {

           
                $entityManager->persist($user)  ;
                $entityManager->flush()  ;

                $this->addFlash(
                    'success' , "Your Profile Was Updated With Success !"
                )  ;
        }
        
      return $this->render('account/profile.html.twig' , [
          'form' => $form->createView()
      ])   ;

    }

     /**
      * Permet de Modifier le mot de Passe 
      *@Route("/account/password-update", name = "account_password")
      * @return Response
      */
    public function updatePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)  {

        $passwordUpdate = new PasswordUpdate()  ;

        $user = $this->getUser()  ;

        $form = $this ->createForm(PasswordUpdateType::class, $passwordUpdate)  ;

        $form->handleRequest($request)  ;

        if($form->isSubmitted()  && $form->isValid())  {
                //1-  verifier que le OldPassword du formulaire est le meme que la database
                if (!password_verify($passwordUpdate->getOldPassword(),$user->getHash()))   {
                     $form->get('oldPassword')->addError(new FormError("You write invalid Password, not yours !!"));
                }  else {
                        $newPassword = $passwordUpdate->getNewPassword()  ;
                        $hash = $encoder->encodePassword($user, $newPassword)  ;
                        $user->setHash($hash)  ;

                        $entityManager->persist($user)  ;
                        $entityManager->flush()  ;

                        $this->addFlash(
                            'success' , "Your Password Was Changed With Success !"
                        )  ;

                        return $this->redirectToRoute('homepage');

                }
                   

        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]) ;
    }
    /**
     * Permet d'afficher le profil de l'utilisqteu connecte 
     *@Route("/account", name = "account_index")
     * @return Response
     */
    public function myAccount()  {
         return  $this->render('user/index.html.twig', [
                'user' => $this->getUser()
          ]);
    }
      
}
