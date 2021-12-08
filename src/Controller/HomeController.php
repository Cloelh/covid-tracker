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


class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="home")
     */
    public function index(DataRepository $dataRepository, LocationRepository $locationRepository): Response
    {
        $dataLive = $dataRepository->getLiveDataFrance();
        $dataDayMinusFour = $dataRepository->getDayMinusFour();
        $dataForOneWeek = $dataRepository->getDataForOneWeek();
        $dataByReg = $locationRepository->getAllDataByReg();
        $dataCasPositifOneWeek = [];
        $dataNewAdmOneWeek = [];
        $dataCriticAdmOneWeek = [];
        $dchosp = [];
        foreach ($dataForOneWeek as $data){
            if($data !== 0){
                array_push($dataCasPositifOneWeek, $data['pos']);
                array_push($dataNewAdmOneWeek, $data['incid_rea']);
                array_push($dataCriticAdmOneWeek, $data['rea']);
                array_push($dchosp, $data['dchosp']);
            } else {
                array_push($dataCasPositifOneWeek, 0);
                array_push($dataNewAdmOneWeek, 0);
                array_push($dataCriticAdmOneWeek, 0);
                array_push($dchosp, 0);
            }
        }

        $pos_7j = [];
        $allReg = [];
        foreach ($dataByReg as $data){
            if($data !== 0){
                array_push($pos_7j, $data['pos']);
                array_push($allReg, $data['name']);
            } else {
                array_push($pos_7j, 0);
                array_push($allReg, 0);
            }
        }

        $allDate = [];
        for ($i = 12; $i >= 4; $i--) {
            $dte = date("d-m-Y", mktime(0, 0, 0, date("m" ), date("d" )-$i, date("Y" )));
            array_push($allDate, $dte);
        }

        return $this->render('main/index.html.twig', [
            'dataLive' => $dataLive,
            'dataMinusFour' => $dataDayMinusFour,
            'dateOneWeek' => json_encode($allDate),
            'allReg' => json_encode($allReg),
            'dataCasPositifOneWeek' => json_encode($dataCasPositifOneWeek),
            'dataNewAdmOneWeek' => json_encode($dataNewAdmOneWeek),
            'dataCriticAdmOneWeek' => json_encode($dataCriticAdmOneWeek),
            'death' => json_encode($dchosp),
            'pos_7j' => json_encode($pos_7j)
        ]);
    }
}