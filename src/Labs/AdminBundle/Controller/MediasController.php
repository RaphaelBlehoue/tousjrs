<?php

namespace Labs\AdminBundle\Controller;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MediasController
 * @package Labs\AdminBundle\Controller
 * @Route("/media")
 */
Class MediasController extends Controller
{

    /**
     * @Route("/{id}/list", name="media_list")
     * @Method("GET")
     * @Template()
     * @ParamConverter("post", class="LabsAdminBundle:Post")
     */
    public function ChoiceMediaInFrontAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('LabsAdminBundle:Post')->getArticles($post);
        if(!$article)
        {
            throw $this->createNotFoundException('l\'article ou les medias n\'existe pas');
        }
        $medias = $em->getRepository('LabsAdminBundle:Media')->findForPostMedia($article);
        return $this->render('LabsAdminBundle:Medias:list.html.twig', [
           'article' => $article,
           'medias' => $medias
        ]);
    }

    /**
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/in/front/{id}", name="add_media_front")
     * @Method("GET")
     */
    public function AddMediaInFrontAction(Media $media)
    {
        $em = $this->getDoctrine()->getManager();
        $medias = $em->getRepository('LabsAdminBundle:Media')->findOneMedia($media);
        if(!$media){
            throw $this->createNotFoundException('Le media photo ou image n\'existe pas');
        }
        $medias->setActived(1);
        $em->flush();
        return $this->redirectToRoute('post_index');
    }
}