<?php


namespace Movie\MovieBundle\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OMDBController extends AbstractFOSRestController
{

    private $api_key;
    private $movie_title;
    private $movie_id;

    public function __construct()
    {
        $this->api_key = "&apikey=61444435";
        $this->movie_title = "Aladdin";
        $this->movie_id = "tt1285016"; // This id relates to "The Social Network
    }

    /**
     * @Route("/film")
     * @return mixed
     * @throws GuzzleException
     *
     */
    public function getFilmByIdAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $request = $client->request('GET', '?i=' . $this->movie_id . $this->api_key);
        $movie = json_decode($request->getBody(), true);

        return $this->handleView($this->view($movie));

        // try to add this data to post action to create a new movie for my
    }

    /**
     * @Route("/filmyear")
     * @return mixed
     * @throws GuzzleException
     *
     */
    public function getFimYearAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $request = $client->request('GET', '?i=' . $this->movie_id . $this->api_key);
        $movie_array = json_decode($request->getBody(), true);

        foreach ($movie_array as $key => $value) {
            if($key === "Year")
            {
                echo $value;
            }
        }

    die();
//        return $this->handleView($this->view($movie_array));

        // try to add this data to post action to create a new movie for my
    }


    /**
     * @Route("/year")
     * @return mixed
     * @throws GuzzleException
     */
    public function getYearAction()
    {
        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $api_key = '&apikey=61444435';
//        $movieTitle = "Aladdin";
        $yearOfRelease = 1996;
        $request = $client->request('GET', '?y=' . $yearOfRelease . $api_key);
        $movies = json_decode($request->getBody(), true);


        return $this->handleView($this->view($movies));
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
        $request = $client->request('GET', '?i=' . $this->movie_id . $this->api_key);
        $movie_array = json_decode($request->getBody(), true);

        foreach ($movie_array as $key => $value) {
            if($key === "Actors")
            {
                $output = $value;
                echo $value;
            }
        }
//        die();
        return $this->handleView($this->view($output));
    }
}