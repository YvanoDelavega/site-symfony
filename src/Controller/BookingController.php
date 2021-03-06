<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * permet d'effectuer une reservation
     * 
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking
        // peut se mettre dans le formulaire directement : donc BookingType dans ce cas
      //  , [            "validation_groups" => ["Default", "Front"]         ]
    );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);


            // si les dates ne sont pas dispo, msg d'erreur 
            if (!$booking->IsBookableDates()) {
                $this->addFlash('warning', "Les dates choisies sont déjà prises");
                // sinon on enregistre
            } else {
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad, 'form' => $form->createView()
        ]);
    }

    /**
     * Affiche le détail d'une réservation et permet de saisir un commentaire
     * @Route("/booking/{id}", name="booking_show")
     */
    public function show(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setAd($booking->getAd())
            ->setAuthor($booking->getBooker());
                $manager->persist($comment);
                $manager->flush();

                $this->addFlash('success', "Votre commentaire a bien été pris en compte");
            }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
