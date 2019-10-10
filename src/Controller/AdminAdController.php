<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Services\Pagination;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * differente possibilité de controle du parametre "page"
     * "\d+" = au moins un chiffre
     * @Route("/admin/ads/{page}", name="admin_ads", requirements={"page":"\d+"})
     * <\d+>?1 = au moins un chiffre, optionnel default = 1
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads")
     * 
     */
    public function index(AdRepository $repo, $page=1, Pagination $pagination)
    {
        // exemples d'utilisation du repository
        /*
        $ad=$repo->find(372);
        $ad=$repo->findOneBy([
            "title" => "Annonce corrigée"
        ]);
        $ad=$repo->findBy([], [], 5, 0); // sans critere, sans ordre, 5 réponses à partir de 0
        // dump($ad);
        */
        $pagination ->setEntityClass(Ad::class)
                    ->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * permet d'éditer une annonce
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return void
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
/*
            On commence par persister les images avant l'annonce
*/
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong> {$ad->getTitle()}</strong> ont bien été enregistrées !"
            );
        }
            
        return $this->render('admin/ad/edit.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    /**
     * permet à l'administrateur de détruire une annonce
     *
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        $titre = $ad->getTitle();
        if($ad->getBookings()){
            $this->addFlash(
                'warning',
                "Impossible de supprimer l'annonce <strong> {$titre}</strong>. Elle a déjà des réservations !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "l'annonce <strong> {$titre}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads');
    }
}


