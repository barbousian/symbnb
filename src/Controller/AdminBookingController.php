<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/ads/bookings", name="admin_ads_bookings")
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();
        return $this->render('admin/ad/bookings/bookings.html.twig', [
            'bookings' => $bookings,
        ]);
    }

     /**
     * Permet de supprimer ue réservation
     * 
     * @Route("admin/ads/bookings/{id}/delete", name="admin_bookings_delete")
     * 
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response
     * 
     */
    public function delete(Booking $booking, ObjectManager $manager) {
        $id=$booking->getId();
        $manager->remove($booking);
        $manager->flush();
        $this->addFlash(
            'success',
            "la réservation <strong> ".$id."</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_ads_bookings');
    }
    /**
     * permet d'éditer une réservation
     * @Route("/admin/ads/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdminBookingType::class, $booking,[
            "validation_groups"=>["Default"]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $booking->setAmount(0); // pour forcer le recalcul du montant
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les modifications de la réservation N°<strong> ".$booking->getId()."</strong> ont bien été enregistrées !"
            );
        }
        return $this->render('admin/ad/bookings/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

}
