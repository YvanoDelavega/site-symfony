<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;


    // pour savoir tout ce que l'on peut injecter au sein d'un service, il faut
    // dans la console :
    // php bin/console debug:container
    // plus précis pour recherche request par exemple :
    // php bin/console debug:container request

    // rq on ne peut pas utiliser Request ici car ca plante donc on utilise RequestStack
    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
        // dump($request);
        // die();
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route //'admin_ads_index'            
        ]);
        
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        return ceil($total / $this->limit);
    }

    public function getData()
    {
        if (empty($this->entityClass)) throw new \Exception("entityClass est null ! il faut utiliser setEntityClass de PaginationService");
        //calculer l'offset
        $start = $this->currentPage * $this->limit - $this->limit;
        //demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $start);
        //renvoyer les éléments en question
        return $data;
    }


    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setRoute($Route)
    {
        $this->Route = $Route;
        return $this;
    }

    public function getRoute()
    {
        return $this->Route;
    }

    public function setPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
