<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * permet d'afficher le formulaire de connexion de l'admin
     * 
     * @Route("/admin/login", name="admin_account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        //  dump($error);
        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error != null,
            'username' => $username
        ]);
    }

        /**
     * permet de se déconnecter du compte admin
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout()
    {
        // rien
    }
}
