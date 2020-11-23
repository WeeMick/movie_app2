<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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

    public function newAction(Request $request) {
        // creates a movie record and adds it to the database
        $movie = new Movie();
        $movie->setTask('Write a blog post');
        $movie->setAddedDate(new \DateTime('today'));

        $form = $this->createFormBuilder($movie)
            ->add('title', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('body', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();

        return $this->render('@MovieMovie/Page/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
