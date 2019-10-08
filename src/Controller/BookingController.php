<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
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
        // appelle le formulaire en précisant à quels groupes de validation, on veut soumettre
        // les asserts des entites
        // mettre ici ou bien dans la function configureOptions du formulaire
        $form = $this->createForm(BookingType::class, $booking, [
            "validation_groups" => ["Default","front"]
        ]);
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
             * charge également le formulaire de saisie d'un commentaire
             * @Route("/booking/{id}", name="booking_show")
             * 
             * @param Booking $booking
             * @param Request $request
             * @param ObjectManager $manager
             *
             * @return Response
             */
            public function show(Booking $booking, Request $request,ObjectManager $manager) {
                $comment=new Comment();
                $formComment = $this->createForm(CommentType::class, $comment);
                $formComment->handleRequest($request);
                if($formComment->isSubmitted() && $formComment->isValid()) {
                    
                    $comment->setAuthor($this->getUser())
                    ->setAd($booking->getAd());
                    $manager->persist($comment);
                    $manager->flush();                    
                    $this->addFlash(
                        'success',
                        'Votre commentaire a bien été pris en compte !'
                    );
        }
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'formComment' => $formComment->createView()
            ]);
    }
}