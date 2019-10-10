<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Services\Pagination;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdCommentController extends AbstractController
{
    /**
     * @Route("/admin/ads/comments/{page<\d+>?1}", name="admin_ads_comments")
     */
    public function index(CommentRepository $repo, $page=1, Pagination $pagination)
    {
        $pagination ->setEntityClass(Comment::class)
                    ->setCurrentPage($page);

        return $this->render('admin/ad/comments/comments.html.twig', [
            'pagination' => $pagination,
        ]);
    }

     /**
     * Permet de supprimer un commentaire
     * 
     * @Route("admin/ads/comments/{id}/delete", name="admin_comments_delete")
     * 
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     * 
     */
    public function delete(Comment $comment, ObjectManager $manager) {
        $id=$comment->getId();
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success',
            "le commentaire <strong> ".$id."</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_ads_comments');
    }
    /**
     * permet d'éditer un commentaire
     * @Route("/admin/ads/comments/{id}/edit", name="admin_comments_edit")
     *
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les modifications du commentaire <strong> ".$comment->getId()."</strong> ont bien été enregistrées !"
            );
        }
        return $this->render('admin/ad/comments/edit.html.twig',[
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

}