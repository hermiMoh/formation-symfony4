<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError() ;
        $username = $utils ->getLastUsername() ;

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de deconneceter l'admin
     * @Route("/admin/logout", name="admin_account_logout")
     * @return void
     */
    public function logout(){
            //...
    }

  
}
