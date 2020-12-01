<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Movie\MovieBundle\Entity\Movie;

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

    public function loginAction()
    {
        return $this->render('@MovieMovie/Page/login_content.html.twig');
    }

    public function registerAction()
    {
        return $this->render('@MovieMovie/Page/register.html.twig');
    }

    /**
     * @Route("/new")
     */
    public function newAction() {
        // creates a movie record and adds it to the database
        $em = $this->getDoctrine()->getManager();
        $movie = new Movie();
        $movie->setTitle('Look, a new Movie');
        $movie->setSummary('Summary of a new Movie');
        $movie->setDetailedDescription('Blah blah blah');
        $movie->setDateAdded(new \DateTime('now'));
        $movie->setRating(2.1);

        $em->persist($movie);
        $em->flush();

        return $this->redirectToRoute('movie_index');

//        $form = $this->createFormBuilder($movie)
//            ->add('title', TextType::class, array('attr' =>
//                array('class' => 'form-control')))
//            ->add('body', TextType::class, array(
//                'required' => false,
//                'attr' => array('class' => 'form-control')))
//            ->add('save', SubmitType::class, array(
//                'label' => 'Create',
//                'attr' => array('class' => 'btn btn-primary mt-2')))
//            ->getForm();
//
//        return $this->render('@MovieMovie/Page/new.html.twig', [
//            'form' => $form->createView(),
//        ]);
    }



}
