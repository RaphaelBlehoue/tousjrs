<?php

namespace Labs\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Labs\AdminBundle\Controller
 * @Route("/dashboard")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * 
     */
    public function indexAction()
    {
        return $this->render('LabsAdminBundle:Default:index.html.twig');
    }
}
