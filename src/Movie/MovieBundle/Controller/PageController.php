<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
//        $movies = array(
//            array(
//                'name' => 'Jaws',
//                'review' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur dolores iure labore obcaecati repudiandae. Tempora?',
//                'rating' => 3
//            ),
//            array(
//                'name' => 'The Nightmare Before Christmas',
//                'review' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur dolores iure labore obcaecati repudiandae. Tempora?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur dolores iure labore obcaecati repudiandae. Tempora?',
//                'rating' => 4
//            )
//        );

        $movies = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->findAll();
        return $this->render('@MovieMovie/Page/index.html.twig', array(
            'movies' => $movies
        ));


    }

    public function aboutAction()
    {
        return $this->render('@MovieMovie/Page/about.html.twig');
    }

}
