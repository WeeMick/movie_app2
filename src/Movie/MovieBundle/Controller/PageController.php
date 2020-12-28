<?php

namespace Movie\MovieBundle\Controller;

use Movie\MovieBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Movie\MovieBundle\Entity\Movie;

class PageController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $movies = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->findAll();
        return $this->render('@MovieMovie/Page/index.html.twig', array(
            'movies' => $movies
        ));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response|null
     */
    public function newAction(Request $request)
    {
        $movie = new Movie();

        $form = $this->createFormBuilder($movie)
            ->setMethod('POST')
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('director', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('summary', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('actors', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('running_time', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $director = $form['director']->getData();
            $summary = $form['summary']->getData();
            $actors = $form['actors']->getData();
            $running_time = $form['running_time']->getData();

            $movie->setTitle($title);
            $movie->setDirector($director);
            $movie->setSummary($summary);
            $movie->setActors($actors);
            $movie->setRunningTime($running_time);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('@MovieMovie/Page/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/movie/review/new")
     * @param $id
     * @return Response|null
     */
    public function newReviewAction(Request $request, $id)
    {
        $newreview = new Review();
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
//        $newreview = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($movie);

        $form = $this->createFormBuilder($newreview)
            ->setMethod('POST')
//            ->add('movie', TextType::class, array('attr' =>
//                array('class' => 'form-control')))
            ->add('review', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('rating', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Save Review',
                'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            $movie = $form['movie']->getData();
            $review = $form['review']->getData();
            $rating = $form['rating']->getData();

            $newreview->setMovie($movie);
            $newreview->setReview($review);
            $newreview->setRating($rating);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newreview);
            $em->flush();

//            return $this->render('@MovieMovie/Page/show.html.twig', array(
//                'movie' => $movie,
//                'review' =>$newreview
//            ));
            return $this->redirectToRoute('movie_show', ['id' => $id] );
        }

        return $this->render('@MovieMovie/Page/newreview.html.twig', [
            'form' => $form->createView(),
            'review' => $newreview,
            'movie' => $movie

        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response|null
     */
    public function editMovieAction(Request $request, $id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);

        $form = $this->createFormBuilder($movie)
            ->setMethod('POST')
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('director', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('summary', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('actors', TextType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('running_time', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $director = $form['director']->getData();
            $summary = $form['summary']->getData();
            $actors = $form['actors']->getData();
            $running_time = $form['running_time']->getData();

            $movie->setTitle($title);
            $movie->setDirector($director);
            $movie->setSummary($summary);
            $movie->setActors($actors);
            $movie->setRunningTime($running_time);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('@MovieMovie/Page/editMovie.html.twig', [
            'form' => $form->createView(), 'movie' => $movie
        ]);

    }

    public function showAction($id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('MovieMovieBundle:Review');
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
        $movieId = $movie->getId();

//         createQueryBuilder() automatically selects FROM AppBundle:Movie
//         and aliases it to "m"
        $query = $repository->createQueryBuilder('r')
            ->setParameters(array(
                'movieId' => $movieId))
            ->where('r.movie = :movieId')
            ->orderBy('r.id', 'ASC')
            ->getQuery();

        $reviews = $query->getResult();
// to get just one result:
// $product = $query->setMaxResults(1)->getOneOrNullResult();





//need to get movie id of movie passed in and then search Review db for records with movie_id
//        $reviews = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->findBy(['movie' = $movieId]);
        return $this->render('@MovieMovie/Page/show.html.twig', array('movie' => $movie, 'reviews' => $reviews));

    }

    public function deleteAction($id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->
        $em->flush();

        // Deleting movie will need to delete all reviews for that movie too
        return $this->render('@MovieMovie/Page/show.html.twig', array('movie' => $movie));

    }

    public function userPageAction($id)
    {
        $user = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($id);
        $reviews = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        return $this->render('@MovieMovie/Page/userpage.html.twig', array('user' => $user,'reviews' => $reviews));
    }

    public function aboutAction()
    {
        return $this->render('@MovieMovie/Page/about.html.twig');
    }

    public function loginAction()
    {
        return $this->render('@MovieMovie/Page/login_content.html.twig');
    }

    public function registerAction()
    {
        return $this->render('@MovieMovie/Page/register.html.twig');
    }


}
