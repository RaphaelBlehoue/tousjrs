<?php

namespace Labs\LimitlessTplBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $dir = __DIR__;
        return $this->render('LabsLimitlessTplBundle:Default:index.html.twig', array(
            'dir' => $dir
        ));
    }
}
