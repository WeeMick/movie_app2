<?php

namespace Movie\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $details = array(
            array(
                'name' => 'mary',
                'email' => 'mary123@hotmail.com'
            ),
            array(
                'name' => 'tim',
                'email' => 'tim@gmail.com'
            )
        );

        return $this->render('@MovieMovie/Page/index.html.twig', array(
            'details' => $details
        ));
    }

    public function aboutAction()
    {
        return $this->render('@MovieMovie/Page/about.html.twig');
    }

}
