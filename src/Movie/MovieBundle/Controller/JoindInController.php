<?php

namespace Movie\MovieBundle\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class JoindInController extends AbstractFOSRestController
{
    /**
     * @Route("/events")
     * @param Request $request
     * @return mixed
     * @throws GuzzleException
     */
    public function getEventsAction()
    {
        $client = new Client(['base_uri' => 'https://api.joind.in']);
        $request = $client->request('GET', 'v2.1/events');
        $events = json_decode($request->getBody(), true);
        
        return $this->handleView($this->view($events));
    }

    /**
     * @Route("/event")
     */
    public function eventAction()
    {
        return $this->render('MovieMovieBundle:JoindIn:event.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/talks")
     */
    public function talksAction()
    {
        return $this->render('MovieMovieBundle:JoindIn:talks.html.twig', array(// ...
        ));
    }

}
