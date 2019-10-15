<?php

namespace App\Controller;

use App\Services\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $stats)
    {
        $stat = $stats->getStats();

        /* idem que la fonction compact
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $users,
                'ads' => $ads,
                'comments' => $comments,
                'bookings' => $bookings
            ]
        ]);
        */
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stat,
            'bestAds' => $stats->getStatsAds('DESC'),
            'piresAds' => $stats->getStatsAds('ASC'),
        ]);
    }
}
