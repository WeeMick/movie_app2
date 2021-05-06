<?php

namespace Movie\MovieBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Movie\MovieBundle\Entity\Review;
use Movie\MovieBundle\Form\ReviewAPIType;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;


class ReviewAPIController extends AbstractFOSRestController
{

    /**
     * @Route("/reviews")
     *
     * This is some test documentation
     *
     * @ApiDoc(
     *     description="Returns an array of all reviews in database",
     *     input="Movie\MovieBundle\Form\ReviewAPIType",
     *     output="Movie\MovieBundle\Entity\Review",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getReviewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('MovieMovieBundle:Review')
            ->findAll();

        return $this->handleView($this->view($reviews));
    }

    /**
     * @param $id
     * @return Response
     * @ApiDoc(
     *     description="Returns a single review from database based on its id",
     *     input="Movie\MovieBundle\Form\ReviewAPIType",
     *     output="Movie\MovieBundle\Entity\Review",
     *     requirements={
     *          {
     *              "name"="id",
     *              "requirement"="\d+",
     *              "description"="Id of the review"
     *          }
     *     },
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
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

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @ApiDoc(
     *     description="Creates a new review entity and persists it to the database",
     *     input="Movie\MovieBundle\Form\ReviewAPIType",
     *     output="Movie\MovieBundle\Entity\Review",
     *     requirements={
     *          {
     *              "name"="id",
     *              "requirement"="\d+",
     *              "description"="Id of the movie to post the review under"
     *          }
     *     },
     *     statusCodes={
     *         201 = "Returned when successful and resource is created",
     *         400 = "Return when not successful"
     *     }
     * )
     */
    public function postReviewAction(Request $request, $id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);

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
            $userId = $form['reviewer']->getData();
            $reviewer = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($userId);
//            $movieId = $form['movie']->getData();
//            $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($movieId);
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
    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @ApiDoc(
     *     description="Updates a review already stored in the database based on the id",
     *     input="Movie\MovieBundle\Form\ReviewAPIType",
     *     output="Movie\MovieBundle\Entity\Review",
     *     requirements={
     *          {
     *              "name"="id",
     *              "requirement"="\d+",
     *              "description"="Id of the review to edit"
     *          }
     *     },
     *     statusCodes={
     *         204 = "Returned when review is updated successfully",
     *         400 = "Return when not successful / bad request",
     *         404 = "Returned when review is not found"
     *     }
     * )
     */
    public function putReviewAction(Request $request, $id)
    {
        $reviewToEdit = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        if (!$reviewToEdit) {
            // no review entry is found, so we set the view
            // to no content and set the status code to 404
            $view = $this->view(null, 404);
            return $this->handleView($view);
        }

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
            return $this->handleView($this->view(null, 200)
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

    /**
     * @param $id
     * @return Response
     * @ApiDoc(
     *     description="Deletes a review from the database based on the id",
     *     output="Movie\MovieBundle\Entity\Review",
     *     requirements={
     *          {
     *              "name"="id",
     *              "requirement"="\d+",
     *              "description"="Id of the review to edit"
     *          }
     *     },
     *     statusCodes={
     *         204 = "Returned when resource is deleted successfully",
     *         404 = "Return when there is no review to delete"
     *     }
     * )
     */
    public function deleteReviewAction($id)
    {
        $review = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        if (!$review) {
            // no review entry is found, so we set the view
            // to no content and set the status code to 404
            $view = $this->view(null, 404);
            return $this->handleView($view);
        } else {
            // the review exists, so we pass it to the view
            // and the status code defaults to 200 "OK"

            $em = $this->getDoctrine()->getManager();
            $em->remove($review);
            $em->flush();

            // Status code 204 shows that The server successfully processed the request, but is not returning
            // any content
            return $this->handleView($this->view(null, 204));
        }

    }

}
