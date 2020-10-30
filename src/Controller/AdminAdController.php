<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    // soit on écrit 
    // @Route ("/admin/ads/{page}", name="admin_ads_index", requirements={"page": "\d+"})
    // public function index(AdRepository $repo, $page = 1)
    // soit onfait comme ci dessous :
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        // $limit = 6;
        // $start = $page * $limit - $limit;
        // $total = count($repo->findAll());
        // $pagesTotal = ceil($total / $limit);
        $pagination->setEntityClass(Ad::class)
            ->setLimit(6)
            ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            // 'ads' => $repo->findBy([], [], $limit, $start),
            // 'pages' => $pagesTotal,
            // 'page' => $page
            'pagination' => $pagination
        ]);
    }


    /**
     * permet d'afficher le formulaire d'édition
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")     
     *
     * @return Response
     */
    public function edit(Ad $a, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $a);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($a);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$a->getTitle()}</strong> a bien été modifiée");

            //  return $this->redirectToRoute('ads_show', ['id' => $a->getId()]);
        }

        return $this->render("admin/ad/edit.html.twig", [
            'form' => $form->createView(),
            'ad' => $a
        ]);
    }




    /**
     * permet de supprimer une annonce
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @return Response
     */
    public function delete(Ad $a, EntityManagerInterface $manager)
    {

        if (count($a->getBookings())) {
            $this->addFlash('warning', "L'annonce <strong>{$a->getTitle()}</strong> ne peut pas être supprimée car elle possède des réservations");
        } else {
            $manager->remove($a);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$a->getTitle()}</strong> a bien été supprimée");
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
