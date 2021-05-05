<?php


namespace Movie\MovieBundle\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OMDBController extends AbstractFOSRestController
{
    /**
     * @Route("/film")
     * @return mixed
     * @throws GuzzleException
     *
     */
    public function getFilmByIdAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $api_key = '&apikey=61444435';
        //example id = tt1285016
        $id = 'tt1285016';
        $request = $client->request('GET', '?i=' . $id . $api_key);
        $movie = json_decode($request->getBody(), true);

        return $this->handleView($this->view($movie));
    }

    /**
     * @Route("/year")
     * @return mixed
     * @throws GuzzleException
     *
     */
    public function getYearOfMovieAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $api_key = '&apikey=61444435';

        $title = "Aladdin";
        $request = $client->request('GET', '?s=' . $title . $api_key);
        $year = json_decode($request->getBody(), true);


        return $this->handleView($this->view($year));
    }

    /**
     * @Route("/actors")
     * @return mixed
     * @throws GuzzleException
     *
     */
    public function getActorsAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $api_key = '&apikey=61444435';

        $title = "Pirates of the Caribbean";
        $request = $client->request('GET', '?s=' . $title . $api_key);
        $movie = json_decode($request->getBody(), true);

        return $this->handleView($this->view($movie));
    }
}