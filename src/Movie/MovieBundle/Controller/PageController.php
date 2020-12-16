<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
//        $movies = array(
//            array(
//                'name' => 'Jaws',
//                'review' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur dolores iure labore obcaecati repudiandae. Tempora?',
//                'rating' => 3
//            ),

        $movies = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->findAll();
        return $this->render('@MovieMovie/Page/index.html.twig', array(
            'movies' => $movies
        ));


    }


    /**
     * @Route("/new")
     */
    public function newAction()
    {
        // creates a movie record and adds it to the database
//        $em = $this->getDoctrine()->getManager();
          $movie = new Movie();
//        $movie->setTitle('Look, a new Movie');
//        $movie->setSummary('Summary of a new Movie');
//        $movie->setDetailedDescription('Blah blah blah');
//        $movie->setDateAdded(new \DateTime('now'));
//        $movie->setRating(2.1);
//
//        $em->persist($movie);
//        $em->flush();
//
//        return $this->redirectToRoute('movie_index');

        $form = $this->createFormBuilder($movie)
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('director', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('summary', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('running_time', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();

        return $this->render('@MovieMovie/Page/new.html.twig', [
            'form' => $form->createView(),
        ]);

//        return $this->render('@MovieMovie/Page/new');

    }

    public function showAction($id)
    {

        $repository = $this->getDoctrine()
            ->getRepository('MovieMovieBundle:Review');

        // createQueryBuilder() automatically selects FROM AppBundle:Movie
        // and aliases it to "m"
//        $query = $repository->createQueryBuilder('m')
//            ->where('m.movie_id = movie')
//            ->orderBy('m.rating', 'ASC')
//            ->getQuery();

//        $products = $query->getResult();
// to get just one result:
// $product = $query->setMaxResults(1)->getOneOrNullResult();

        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);

//need to get movie id of movie passed in and then search Review db for records with movie_id
        $review = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($movie);
        return $this->render('@MovieMovie/Page/show.html.twig', array('movie' => $movie, 'review' => $review));

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

    /**
     * @Route("/edit/{id}")
     */
    public function editAction()
    {
        return $this->render('@MovieMovie/Page/edit.html.twig');
    }


}
