<?php

namespace Labs\AdminBundle\Controller;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MediasController
 * @package Labs\AdminBundle\Controller
 * @Route("/media")
 */
Class MediasController extends Controller
{

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @Route("/upload/{articles}", name="upload_media_image")
     * @ParamConverter("post", class="LabsAdminBundle:Post", options={"articles"="id"})
     * @Method({"GET","POST"})
     */
    public function AddAction(Request $request, Post $post)
    {
        dump($post);
        dump($request);
        die;
    }
}