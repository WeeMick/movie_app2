<?php

namespace Movie\MovieBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Movie\MovieBundle\Entity\Review;
use Movie\MovieBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;

class ReviewAPIController extends AbstractFOSRestController
{

    public function getReviewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('MovieMovieBundle:Review')
            ->findAll();

        return $this->handleView($this->view($reviews));
    }

    public function getReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('MovieMovieBundle:Review')
            ->find($id);
//        dump($review); die();
        if (!$review) {
            // no review entry is found, so we set the view
            // to no content and set the status code to 404
            $view = $this->view(null, 404);
        } else {
            // the review exists, so we pass it to the view
            // and the status code defaults to 200 "OK"
            $view = $this->view($review);
        }
        return $this->handleView($view);
    }

    public function postReviewAction(Request $request, $id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
//        $userId = $this->getUser()->getId();
//        $reviewer = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($userId);
        $new_review = new Review();

        $form = $this->createForm(ReviewType::class, $new_review);

        // Point 1 of list above
        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form['review']->getData();
            $rating = $form['rating']->getData();

            $new_review->setReview($review);
            $new_review->setRating($rating);
            $new_review->setMovie($movie);
//            $new_review->setReviewer($reviewer);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            // set status code to 201 and set the Location header
            // to the URL to retrieve the review entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation($this->generateUrl('api_review_get_reviews',
                    ['id' => $new_review->getId()]
                    )
                )
            );

        } else {
            // the form isn't valid so return the form
            // along with a 400 status code
            return $this->handleView($this->view($form, 400));
        }


    }

    public function putReviewAction()
    {

    }

}

