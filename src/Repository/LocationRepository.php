<?php

namespace App\Repository;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationRepository
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAllDataByDep(): array
    {
        $response = $this->client->request(
            'GET',
            'https://coronavirusapifr.herokuapp.com/data/departements-by-date/04-10-2021'
        );

        $content = $response->toArray();

        $posByDep = [];

        foreach ($content as $value){
            if ($value['pos'] < 10){
                $color = '#3ed667';
            } elseif($value['pos'] < 30){
                $color = '#d6c23e';
            } elseif($value['pos'] < 50){
                $color = '#cf6b1f';
            } else{
                $color = '#800909';
            }
            array_push(
                $posByDep,
                array(
                    $value['dep'] => [
                        'colorIndic' => $color,
                    ]
                )
            );
        }

        return $posByDep;
    }

    public function getAllDataByReg(): array
    {
        $response = $this->client->request(
            'GET',
            'https://coronavirusapifr.herokuapp.com/data/departements-by-date/04-10-2021'
        );

        $data = [
            84 => ['name' => 'Auvergne et Rhône-Alpes', 'pos' => 0],
            32 => ['name' => 'Hauts-de-France', 'pos' => 0],
            93 => ['name' => 'Provence-Alpes-Côte d\'Azur', 'pos' => 0],
            44 => ['name' => 'Grand Est', 'pos' => 0],
            76 => ['name' => 'Occitanie', 'pos' => 0],
            28 => ['name' => 'Normandie', 'pos' => 0],
            75 => ['name' => 'Nouvelle Aquitaine', 'pos' => 0],
            24 => ['name' => 'Centre-Val de Loire', 'pos' => 0],
            27 => ['name' => 'Bourgogne et Franche-Comté', 'pos' => 0],
            53 => ['name' => 'Bretagne', 'pos' => 0],
            94 => ['name' => 'Corse', 'pos' => 0],
            52 => ['name' => 'Pays de la Loire', 'pos' => 0],
            11 => ['name' => 'Île-de-France', 'pos' => 0],
            6 => ['name' => 'Mayotte', 'pos' => 0],
            4 => ['name' => 'Réunion', 'pos' => 0],
            3 => ['name' => 'Guyane', 'pos' => 0],
            2 => ['name' => 'Martinique', 'pos' => 0],
            1 => ['name' => 'Guadeloupe', 'pos' => 0],
        ];

        $content = $response->toArray();

        foreach ($data as $value){
            foreach ($content as $byDep){
                if ($value['name'] === $byDep['lib_reg']){
                    $data[$byDep['reg']]['pos'] = $data[$byDep['reg']]['pos'] + $byDep['pos_7j'];
                }
            }
        }

        return $data;
    }

    public function getAllDataByOneDep($dep): array{
        $response = $this->client->request(
            'GET',
            'https://coronavirusapifr.herokuapp.com/data/live/departement/'.$dep
        );

        $content = $response->toArray();

        return $content;
    }
}