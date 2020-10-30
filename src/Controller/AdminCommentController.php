<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function index(CommentRepository $repo)
    {
        $comments = $repo->findAll();
        return $this->render('admin/comment/index.html.twig',[
            'comments' => $comments
        ]);
    }

/**
 * permet à un administrateur de modifier un commentaire
 * @Route("admin/comments/{id}/edit", name="admin_comment_edit")
 * 
 * @param Comment $c
 * @return void
 */
    public function Edit(Comment $c, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $c);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($c);
            $manager->flush();

            $this->addFlash('success', "Le commentaire <strong>{$c->getId()}</strong> a bien été modifié");
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $c,
            'form' => $form->createView()
        ]);
    }


    /**
     * Supprimer un commentaire depuis l'interface d'administration
     * @Route("admin/comments/{id}/delete", name="admin_comment_delete")
     * 
     */
    public function delete(Comment $c, EntityManagerInterface $manager)
    {
        $manager->remove($c);
        $manager->flush();
        $this->addFlash('success', "Le commentaire <strong>{$c->getAuthor()->getFullName()}</strong> a bien été supprimé !" );

        return $this->redirectToRoute('admin_comments_index');
    }
}
