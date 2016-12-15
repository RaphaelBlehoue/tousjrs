<?php

namespace Labs\AdminBundle\Controller;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MediaController
 * @package Labs\AdminBundle\Controller
 * @Route("/Media")
 */
Class MediasController extends Controller
{

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @Route("/upload/{posts}/media", name="upload_Media")
     * @Method({"GET","POST"})
     */
    public function AddAction(Request $request, Post $post)
    {

        $em = $this->getDoctrine()->getManager();
        $media = new Media();
        $posts = $em->getRepository('LabsAdminBundle:Post')->getPost($post);
        if(null === $posts)
        {
            throw new NotFoundHttpException('Votre article est introuvable');
        }
        dump($posts);
        die;

       // if($request->isXmlHttpRequest()){
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
           /* $file = $request->files->get('file');
            dump($file);
            die();
            $fileName = $posts->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->container->getParameter('gallery_directory'),
                $fileName
            );*/
           /* $media->setUrl($fileName);
            $media->setDossier($posts);
            $em->persist($media);
            $em->flush($media);
            $response = ['success' => 'true'];
            return new JsonResponse($response);*/
       // }
    }
}