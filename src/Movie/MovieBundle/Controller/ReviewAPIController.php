<?php

namespace Movie\MovieBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Movie\MovieBundle\Entity\Review;
use Movie\MovieBundle\Form\ReviewAPIType;
use Symfony\Component\HttpFoundation\Request;


class ReviewAPIController extends AbstractFOSRestController
{

    /**
     * @Route("/reviews")
     */
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

        $user = $this->getUser();

        if ($user) {
            $userId = $this->getUser()->getId();
            $reviewer = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($userId);
        } else {
            $reviewer = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find(7);

        }


        $new_review = new Review();

        $form = $this->createForm(ReviewAPIType::class, $new_review);

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
            $new_review->setReviewer($reviewer);

            $em = $this->getDoctrine()->getManager();
            $em->persist($new_review);
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



// PUT - If an existing resource is modified, either the 200 (OK) or 204 (No Content) response
// codes SHOULD be sent to indicate successful completion of the request.
    public function putReviewAction(Request $request, $id)
    {
        $reviewToEdit = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

//        if (!$reviewToEdit) {
//            // no review entry is found, so we set the view
//            // to no content and set the status code to 404
//            $view = $this->view(null, 404);
//        } else {
//            // the review exists, so we pass it to the view
//            // and the status code defaults to 200 "OK"
//            $view = $this->view($reviewToEdit);
//        }

        // TODO check if I need to use $this->>handle($view)

        // TODO If review->reviewer != logged in user,
        // error - not authorised to code 401

        $form = $this->createForm(ReviewAPIType::class, $reviewToEdit);

        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form['review']->getData();
            $rating = $form['rating']->getData();

            $reviewToEdit->setReview($review);
            $reviewToEdit->setRating($rating);


            $em = $this->getDoctrine()->getManager();
            $em->persist($reviewToEdit);
            $em->flush();

            // set status code to 201 and set the Location header
            // to the URL to retrieve the review entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation($this->generateUrl('api_review_get_reviews',
                    ['id' => $reviewToEdit->getId()]
                )
                )
            );
        } else {
            // the form isn't valid so return the form
            // along with a 400 status code
            return $this->handleView($this->view($form, 400));
        }
    }


//DELETE - A successful response SHOULD be 200 (OK) if the response includes an entity describing
// the status, 202 (Accepted) if the action has not yet been enacted, or 204 (No Content) if the
// action has been enacted but the response does not include an entity.

    public function deleteReviewAction($id)
    {
        $review = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('movie_index'));

    }

}

// HTTP 200 OK: Standard response for successful HTTP requests. The actual response will
// depend on the request method used.
//
//HTTP 204 No Content: The server successfully processed the request, but is not returning
// any content
