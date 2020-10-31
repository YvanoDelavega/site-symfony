<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        return $this->render('admin/dashboard/index.html.twig', [            
            'stats' => $statsService->getStats(),
            'bestAds' => $statsService->getBestAds(),
            'worstAds' => $statsService->getWorstAds()
        ]);
    }
}
