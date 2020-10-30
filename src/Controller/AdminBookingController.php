<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * permet d'éditer une réservation
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @param Booking $booking
     * @return void
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        // on dit au formulaire d'analyse la request (faire sa validation par exemple)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on met à 0 pour qu'il soit recalculé automatiquement par la function prePersist() dand Booking.php (grace à @ORM\PreUpdate)
            $booking->setAmount(0);
           // $manager->persist($booking); optionnel car le booking existe deja et lme manager l'a deja pris en compte
            $manager->flush();
            $this->addFlash('success', "La réservation <strong>{$booking->getId()}</strong> a bien été modifiée");
            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    
    /**
     * Supprimer une réservation  depuis l'interface d'administration
     * @Route("admin/comments/{id}/delete", name="admin_booking_delete")
     * 
     */
    public function delete(Booking $c, EntityManagerInterface $manager)
    {
        $manager->remove($c);
        $manager->flush();
        $this->addFlash('success', "La réservation a bien été supprimé !" );

        return $this->redirectToRoute('admin_booking_index');
    }


}
