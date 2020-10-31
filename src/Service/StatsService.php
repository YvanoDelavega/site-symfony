<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\User a')->getSingleScalarResult();
    }

    public function getAdsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {

        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Comment a')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {

        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Booking a')->getSingleScalarResult();
    }

    public function getStats()
    {
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();


        // on peut écrire les 4 lignes ci dessous plus simplement :
        //                
        // 'stats' =>['users' => $users,
        // 'ads' => $ads,
        // 'bookings' => $bookings,
        // 'comments' => $comments]
        //
        //'stats' => compact('users', 'ads', 'bookings', 'comments'),

        return compact('users', 'ads', 'bookings', 'comments');
    }

    private function getAdsStat($order)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture 
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $order
        )->setMaxResults(5)->getResult();
    }

    public function getBestAds()
    {
        return $this->getAdsStat('DESC');
    }

    public function getWorstAds()
    {
        return $this->getAdsStat('ASC');
    }
}
