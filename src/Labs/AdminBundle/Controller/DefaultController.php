<?php

namespace Labs\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Labs\AdminBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('LabsAdminBundle:Default:index.html.twig');
    }
}
