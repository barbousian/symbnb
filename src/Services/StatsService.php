<?php

namespace App\Services;

use Doctrine\Common\Persistence\ObjectManager;

class StatsService {
    private $manager;

    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
    }

    public function getAdsCount() {
        return $this->manager->createQuery('Select count(u) from App\Entity\Ad u')->getSingleScalarResult();
    }

    public function getCommentsCount() {
        return $this->manager->createQuery('Select count(u) from App\Entity\Comment u')->getSingleScalarResult();
    }

    public function getBookingsCount() {
        return $this->manager->createQuery('Select count(u) from App\Entity\Booking u')->getSingleScalarResult();
    }

    public function getUsersCount() {
        $users = $this->manager->createQuery('Select count(u) from App\Entity\User u')->getSingleScalarResult();
        return $users;
    }

    public function getStats() {
        $stats = [
            'ads' => $this->getAdsCount(),
            'users' => $this->getUsersCount(),
            'bookings' => $this->getBookingsCount(),
            'comments' => $this->getCommentsCount()
        ];
        return $stats;
    }

    public function getStatsAds($order) {
        $statsAds = $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note '.$order
            )
                ->setMaxResults(5)
                ->getResult();
        return $statsAds;
    }
}
