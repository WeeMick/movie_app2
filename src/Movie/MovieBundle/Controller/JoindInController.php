<?php

namespace Movie\MovieBundle\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class JoindInController extends AbstractFOSRestController
{
    /**
     * @Route("/events")
     * @return mixed
     * @throws GuzzleException
     */
    public function getEventsAction()
    {
        $client = new Client(['base_uri' => 'https://api.joind.in']);
        $request = $client->request('GET', 'v2.1/events?filter=past');
        $events = json_decode($request->getBody(), true);

        return $this->handleView($this->view($events));
    }

    /**
     * @Route("/event")
     * @param $id
     * @return Response
     * @throws GuzzleException
     */
    public function getEventAction($id)
    {
        $client = new Client(['base_uri' => 'https://api.joind.in']);
        $response = $client->request('GET', 'v2.1/events', ['id' => $id]);
        $event = json_decode($response->getBody(), true);

        return $this->handleView($this->view($event));

//        return $this->render('MovieMovieBundle:JoindIn:event.html.twig', array(// ...
//        ));
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
