<?php
namespace Movie\MovieBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class MovieAPIController extends AbstractFOSRestController
{

    public function getMoviesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('MovieMovieBundle:Movie')
            ->findAll();

        return $this->handleView($this->view($movies));
    }

    public function getMovieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('MovieMovieBundle:Movie')
            ->find($id);

        if(!$movie) {
            // no blog entry is found, so we set the view
            // to no content and set the status code to 404
            $view = $this->view(null, 404);
        } else {
            // the blog entry exists, so we pass it to the view
            // and the status code defaults to 200 "OK"
            $view = $this->view($movie);
        }
        return $this->handleView($view);
    }

}

