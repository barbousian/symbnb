<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/ads/{slug}/book", name="ads_book")
     * @Security("is_granted('ROLE_USER') ")
     * @return Response
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager) {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $booking->setBooker($this->getUser())
                    ->setAd($ad);
            // si les dates ne sont pas disponibles -> erreur
            if(!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    'les dates sont déjà prises !'
                );
            } else {
                $manager->persist($booking);
                $manager->flush();
                /* quand un parametre n'est pas prévu dans une route, il se rajoute en parametre d'URL classique avec ?
                */
                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }
            
        return $this->render('booking/book.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    /**
     * permet d'afficher une réservation
     * @Route("/booking/{id}", name="booking_show")
     * 
     * @param Booking $booking
     *
     * @return Response
     */
    public function show(Booking $booking) {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}