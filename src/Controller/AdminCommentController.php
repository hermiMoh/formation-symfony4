<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher la liste des commentaires pour l'administrateur 
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setLimit(5)
                   ->setPage($page) ; 
        return $this->render('admin/comment/index.html.twig', [
           // 'comments' => $repo->findAll()
           'pagination' => $pagination
        ]);
    }

    /**
     * Permet de changer le contenu d'un commentaire 
     * 
     * @Route("/admin/comments/{id}/edit", name = "admin_comment_edit")
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(AdminCommentType::class, $comment) ;

        $form->handleRequest($request) ;

        if ($form->isSubmitted() && $form->isValid())  {
                $em->persist($comment) ;
                $em->flush() ;

                $this->addFlash(
                    'success', 
                    "Comment Number :  <strong>{$comment->getId()}</strong>  was edited with Success !"
                ) ;
        }

        return $this->render('/admin/comment/edit.html.twig',[
            'comment' => $comment,
            'form' =>$form->createView()
        ])  ;
            
    }

    /**
     * Permet de Supprimer un commentaire 
     * @Route("/admin/comments/{id}/delete", name = "admin_comment_delete")
     *
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $em)  {
        $em->remove($comment) ;
        $em->flush() ;

        $this->addFlash(
            'success', 
            "Comment of :  <strong>{$comment->getAuthor()->getFullName()}</strong>  was DELETED with Success !"
        ) ;
            return $this->redirectToRoute('admin_comment_index') ;
    }
}
