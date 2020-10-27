<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo/*, SessionInterface $session*/)
    {
        //dump($session);
        //  $repo = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);

        // return $this->render('ad/index.html.twig', [
        //     'controller_name' => 'AdController',
        // ]);
    }



    // /**
    //  * 
    //  * permet d'afficher une seule annonce
    //  * 
    //  * @Route("/ads/{slug}", name="ads_show" )
    //  * 
    //  * @return Response
    //  */
    // public function show($slug, AdRepository $repo)
    // {
    //     $ad = $repo->findOneBySlug($slug);
    //     return $this->render(
    //         'ad/show.html.twig',
    //         [
    //             'ad' => $ad
    //         ]
    //     );

    // }


    /**
     * permet de créer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * 
     * return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $a = new Ad();
        $form = $this->createForm(AnnonceType::class, $a);
        $form->handleRequest($request);

        // dump($a);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($a->getImages() as $img) {
                $img->setAd($a);
                $manager->persist($img);
            }

            $a->setAuthor($this->getUser());

            $manager->persist($a);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$a->getTitle()}</strong> a bien été enregistrée");

            return $this->redirectToRoute('ads_show', ['slug' => $a->getSlug()]);
        }

        return $this->render('ad/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * permet d'afficher le formulaire d'édition
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === a.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez donc pas la modifier !")
     *
     * @return Response
     */
    public function edit(Ad $a, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $a);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($a->getImages() as $img) {
                $img->setAd($a);
                $manager->persist($img);
            }

            $manager->persist($a);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$a->getTitle()}</strong> a bien été modifiée");

            return $this->redirectToRoute('ads_show', ['slug' => $a->getSlug()]);
        }

        return $this->render("ad/edit.html.twig", [
            'form' => $form->createView(),
            'ad' => $a
        ]);
    }




/**
     * permet de supprimer une annonce
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === a.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez donc pas la supprimer !")
     *
     * @return Response
     */
    public function delete(Ad $a, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($a);
        $manager->flush();

        $this->addFlash('success', "L'annonce <strong>{$a->getTitle()}</strong> a bien été supprimée");

        return $this->redirectToRoute('ads_index');
    }


    /**
     * 
     * permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name="ads_show" )
     * 
     * @return Response
     */
    public function show(Ad $ad) // utilisation du paramconverter de symfony
    {
        return $this->render(
            'ad/show.html.twig',
            [
                'ad' => $ad
            ]
        );
    }
}
