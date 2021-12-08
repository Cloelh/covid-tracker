<?php

namespace App\Controller;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DataRepository;


class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(LocationRepository $locationRepository): Response
    {
        $dataDep = $locationRepository->getAllDataByDep();

        // dd($dataDep[37]);

        return $this->render('dashboard/index.html.twig', [
            'dataDep' => $dataDep,
            'depDetail' => false
        ]);
    }

    /**
     * @Route("/singleDep/{dep}", name="singleDep")
     */
    public function singleDep(LocationRepository $locationRepository, $dep): Response
    {

        $dataOneDep = $locationRepository->getAllDataByOneDep($dep);
        $dataDep = $locationRepository->getAllDataByDep();


        return $this->render('dashboard/index.html.twig', [
            'dataDep' => $dataDep,
            'dataOneDep' => $dataOneDep[0],
            'depDetail' => true
        ]);
    }
}