<?php

namespace Movie\MovieBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Movie\MovieBundle\Entity\Movie;
use Movie\MovieBundle\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;

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

        if (!$movie) {
            // no movie entry is found, so we set the view
            // to no content and set the status code to 404
            $view = $this->view(null, 404);
        } else {
            // the movie exists, so we pass it to the view
            // and the status code defaults to 200 "OK"
            $view = $this->view($movie);
        }
        return $this->handleView($view);
    }

    public function postMovieAction(Request $request)
    {
        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        // Point 1 of list above
        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $director = $form['director']->getData();
            $summary = $form['summary']->getData();
            $actors = $form['actors']->getData();
            $running_time = $form['running_time']->getData();
//            $image_file = $form->get('image_file')->getData();

            $movie->setTitle($title);
            $movie->setDirector($director);
            $movie->setSummary($summary);
            $movie->setActors($actors);
            $movie->setRunningTime($running_time);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            // set status code to 201 and set the Location header
            // to the URL to retrieve the blog entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation($this->generateUrl('api_movie_get_movie',
                    ['id' => $movie->getId()]
                    )
                )
            );

        } else {
            // the form isn't valid so return the form
            // along with a 400 status code
            return $this->handleView($this->view($form, 400));
        }


    }

}

