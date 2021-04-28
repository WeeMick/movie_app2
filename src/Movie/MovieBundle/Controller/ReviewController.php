<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReviewController extends Controller
{
    public function viewAction()
    {
        return $this->render('MovieMovieBundle:Review:view.html.twig', array(
            // ...
        ));
    }

    public function createAction()
    {
        return $this->render('MovieMovieBundle:Review:create.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('MovieMovieBundle:Review:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->redirect($this->generateUrl('movie_index'));
    }

}
