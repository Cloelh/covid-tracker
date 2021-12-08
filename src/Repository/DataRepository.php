<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataRepository
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

    }

    public function getLiveDataFrance(): array
    {
        $response = $this->client->request(
            'GET',
            'https://coronavirusapifr.herokuapp.com/data/live/france'
        );

        $content = $response->toArray();

        return $content[0];
    }

    public function getDayMinusFour(): array
    {

        $dte_4 = date("d-m-Y", mktime(0, 0, 0, date("m" ), date("d" )-4, date("Y" )));

        $response = $this->client->request(
            'GET',
            'https://coronavirusapifr.herokuapp.com/data/france-by-date/'.$dte_4
        );

        $content = $response->toArray();

        return $content[0];
    }

    public function getDataForOneWeek(): array
    {
        $allData = [];
        for ($i = 12; $i >= 4; $i--) {
            $dte = date("d-m-Y", mktime(0, 0, 0, date("m" ), date("d" )-$i, date("Y" )));
            try {
                $response = $this->client->request(
                    'GET',
                    'https://coronavirusapifr.herokuapp.com/data/france-by-date/'.$dte
                );
                $content = $response->toArray();
                array_push($allData, $content[0]);
            } catch ( \Symfony\Component\HttpClient\Exception\TransportException | \Exception | \Throwable $exception ) {
                array_push($allData, 0);
            }
        }

        return $allData;
    }
}
