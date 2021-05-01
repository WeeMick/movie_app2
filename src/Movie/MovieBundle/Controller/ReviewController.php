<?php

namespace Movie\MovieBundle\Controller;

use Knp\Component\Pager\Paginator;
use Movie\MovieBundle\Entity\Review;
use Movie\MovieBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function viewAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('MovieMovieBundle:Review');
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
        $movieId = $movie->getId();

        //         createQueryBuilder() automatically selects FROM AppBundle:Movie
        //         and aliases it to "r"
        $query = $repository->createQueryBuilder('r')
            ->setParameters(array(
                'movie' => $movieId))
            ->where('r.movie = :movie')
            ->orderBy('r.id', 'ASC')
            ->getQuery();

        /**
         * @var $paginator Paginator
         */
        $paginator = $this->get('knp_paginator');

        $reviews = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 3)
        );

        return $this->render('@MovieMovie/Page/show.html.twig', array('movie' => $movie, 'reviews' => $reviews));

    }
    /*
     * End of viewAction
     */

    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function createAction(Request $request, $id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
        $userId = $this->getUser()->getId();
        $reviewer = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($userId);
        $newreview = new Review();
        $form = $this->createForm(ReviewType::class, $newreview);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form['review']->getData();
            $rating = $form['rating']->getData();

            $newreview->setReview($review);
            $newreview->setRating($rating);
            $newreview->setMovie($movie);
            $newreview->setReviewer($reviewer);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newreview);
            $em->flush();

            return $this->redirectToRoute('movie_show', array(
                'id' => $movie->getId()
            ));

        }

        return $this->render('MovieMovieBundle:Review:create.html.twig', array(
            'form' => $form->createView(),
            'movie' => $movie
        ));
    }
    /*
    * End of createAction
    */

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response|null
     */
    public function editAction(Request $request, $id)
    {
        $reviewToEdit = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);
        $form = $this->createForm(ReviewType::class, $reviewToEdit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form['review']->getData();
            $rating = $form['rating']->getData();

            $reviewToEdit->setReview($review);
            $reviewToEdit->setRating($rating);


            $em = $this->getDoctrine()->getManager();
            $em->persist($reviewToEdit);
            $em->flush();

            return $this->redirectToRoute('movie_show', array(
                'id' => $reviewToEdit->getMovie()->getId()
            ));
        }

        return $this->render('@MovieMovie/Review/edit.html.twig', [
            'form' => $form->createView(),
            'review' => $reviewToEdit
        ]);
    }

    /*
   * End of editAction
   */

    public function deleteAction($id)
    {
        $review = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('movie_index'));

    }

}
