<?php

namespace Movie\MovieBundle\Controller;

use Knp\Component\Pager\Paginator;
use Movie\MovieBundle\Entity\Review;
use Movie\MovieBundle\Form\MovieType;
use Movie\MovieBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Movie\MovieBundle\Entity\Movie;
use Symfony\Component\Validator\Constraints\File;

class PageController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return Response|null
     */
    public function indexAction(Request $request)
    {
//        $movies = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->findAll();

        $repository = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie');

//        Get the last 5 movies added to the database - for sidebar content
        $sidebarQuery = $repository
            ->createQueryBuilder("m")
            ->orderBy("m.id", "DESC")
            ->setMaxResults(5)
            ->getQuery();

        $releases = $sidebarQuery->getResult();

        /**
         * @var $paginator Paginator
         */
        $paginator = $this->get('knp_paginator');

        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT m FROM MovieMovieBundle:Movie m";
        $query = $em->createQuery($dql);

        $movies = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 10)
        );

        return $this->render('@MovieMovie/Page/index.html.twig', array(
            'movies' => $movies,
            'releases' => $releases
        ));
    }
    /*
     * End of indexAction
     */

    /**
     * @param Request $request
     * @return RedirectResponse|Response|null
     */
    public function newAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $director = $form['director']->getData();
            $summary = $form['summary']->getData();
            $actors = $form['actors']->getData();
            $running_time = $form['running_time']->getData();
            $image_file = $form->get('image_file')->getData();

            if ($image_file) {
                echo "image got";
                $originalFilename = pathinfo($image_file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image_file->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $image_file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    // updates the 'image_filename' property to store the file name
                    // instead of its contents
                    $movie->setImageFile($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    echo "Exception: " . $e;
                }

            }

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
    /*
    * End of newAction
    */


    public function searchAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('MovieMovieBundle:Movie');

        // need to create a query for the movies?
        $movies = null;

        $searchQuery = $request->query->get('search');

        $query = $repository->createQueryBuilder('m')
            ->setParameters(array(
                'searchQuery' => '%'.$searchQuery.'%'))
            ->where('m.title LIKE :searchQuery')
            ->orderBy('m.id', 'ASC')
            ->getQuery();

        /**
         * @var $paginator Paginator
         */
        $paginator = $this->get('knp_paginator');

        $movies = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 3)
        );

        return $this->render('@MovieMovie/Page/search.html.twig', array(
            'query' => $searchQuery,
            'movies' => $movies));
    }
    /*
     * End of searchAction
     */


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response|null
     */
    public function editMovieAction(Request $request, $id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $director = $form['director']->getData();
            $summary = $form['summary']->getData();
            $actors = $form['actors']->getData();
            $running_time = $form['running_time']->getData();
            $image_file = $form['image_file']->getData();

            $movie->setTitle($title);
            $movie->setDirector($director);
            $movie->setSummary($summary);
            $movie->setActors($actors);
            $movie->setRunningTime($running_time);
            $movie->setImageFile($image_file);

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('@MovieMovie/Page/editMovie.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie
        ]);

    }
    /*
    * End of editMovieAction
    */


    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function movieAction(Request $request, $id)
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

        return $this->render('@MovieMovie/Page/movie.html.twig', array('movie' => $movie, 'reviews' => $reviews));


    }
    /*
    * End of showAction
    */

    /**
     * @return Response|null
     */
    public function newReleaseAction()
    {
//        $em = $this->getDoctrine()->getManager();

//        // Create SQL query which selects the last 5 movies added to database
//        $RAW_QUERY = 'SELECT TOP 5 FROM movie ORDER BY id DESC;';
//        $statement = $em->getConnection()->prepare($RAW_QUERY);
//        $statement->execute();
//        $movies = $statement->fetchAll();

        $repository = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie');


        $query = $repository->createQueryBuilder()
            ->createQueryBuilder("m")
            ->orderBy("id", "DESC")
            ->setMaxResults(5)
            ->getQuery()
            ->getOneOrNullResult();

        $movies = $query->getResult();

        return $this->render('@MovieMovie/Page/index.html.twig', array('movies' => $movies));

    }
    /*
    * End of newReleaseAction
    */


    // This function is not currently working properly - not required in the assignment spec
    /**
     * @param $id
     * @return Response|null
     */
    public function deleteAction($id)
    {
        $movie = $this->getDoctrine()->getRepository('MovieMovieBundle:Movie')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->
        $em->flush();

        // TODO Deleting movie will need to delete all reviews for that movie too
        return $this->render('movie.html.twig', array('movie' => $movie));

    }
    /*
    * End of deleteAction
    */

    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function userPageAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('MovieMovieBundle:User')->find($id);

//        $reviews = $this->getDoctrine()->getRepository('MovieMovieBundle:Review')->find($id);

        $repository = $this->getDoctrine()
            ->getRepository('MovieMovieBundle:Review');

        $query = $repository->createQueryBuilder('r')
            ->setParameters(array(
                'userId' => $user))
            ->where('r.reviewer = :userId')
            ->getQuery();

        $paginator = $this->get('knp_paginator');

        $reviews = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 3)
        );


        return $this->render('@MovieMovie/Page/userpage.html.twig', array('user' => $user, 'reviews' => $reviews));
    }
    /*
    * End of userPageAction
    */

    /**
     * @return Response|null
     */
    public function aboutAction()
    {
        return $this->render('@MovieMovie/Page/about.html.twig');
    }
    /*
    * End of aboutAction
    */

    /**
     * @return Response|null
     */
    public function loginAction()
    {
        return $this->render('@MovieMovie/Page/login_content.html.twig');
    }
    /*
    * End of loginAction
    */

    /**
     * @return Response|null
     */
    public function registerAction()
    {
        return $this->render('@MovieMovie/Page/register.html.twig');
    }
    /*
    * End of registerActionAction
    */


}
