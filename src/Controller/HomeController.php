<?php

namespace App\Controller;


use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//Symfony\Bundle\FrameworkBundle\Controller\Controller has been deprecated since v4.2.0 and removed since v5.0.0, use Symfony\Bundle\FrameworkBundle\Controller\AbstractController instead.
//use Symfony\Bundle\FrameworkBundle\Controller\Controller

class HomeController extends AbstractController
{


    /**
     * 
     * @Route("/hello/{prenom}/age/{age}", name ="hello")
     * @route("/hello", name ="hellobase")
     * @route("/hello/{prenom}", name ="hello_prenom")
     * montre la page qui dit bonjour
     * 
     * @ return void
     */
    public function hello($prenom = "anonyme", $age = 0)
    {
        //  return new Response("bonjour " . $prenom . " " . $age . " ans");

        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }


    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo)
    {
        //return new Response("<h1>bonjour</h1>");

        // $prenoms = ["po", "lo", "yiyi"];
        // $age = ["po" => 1, "lo" => 2, "yiyi" => 23];

        return $this->render(
            'home.html.twig',
            [
                'ads'=>$adRepo->findBestAds(3),
                'users'=>$userRepo->findBestUsers(4)
                // 'title' => "bonjour a tous", 'age' => 6,
                // 'tab' => $prenoms,
                // 'tab2' => $age

            ]

        );
    }
}
