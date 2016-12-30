<?php

namespace Labs\AdminBundle\Controller;
use Labs\AdminBundle\Entity\Format;
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
     * @Route("/{id}/dossier", name="media_dossier")
     * @Method("GET")
     * @Template()
     * @ParamConverter("format", class="LabsAdminBundle:Format")
     */
    public function ChoiceMediaDossierInFrontAction(Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = $em->getRepository('LabsAdminBundle:Format')->getFormats($format);
        if(!$dossier)
        {
            throw $this->createNotFoundException('le dossiers ou les medias n\'existe pas');
        }
        $medias = $em->getRepository('LabsAdminBundle:Media')->findForDossierMedia($dossier);
        return $this->render('LabsAdminBundle:Medias:list_dossier.html.twig', [
            'dossier' => $dossier,
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
        //Rechercher tous les medias qui ont la même clé etrangère sauf celle de l'id
        $oldMedia = $em->getRepository('LabsAdminBundle:Media')->findMediaIsNotMedia($medias->getId(), $medias->getPost());
        //Mettre la valeur de toute les valeurs trouvée a active = 0
        foreach ($oldMedia as $m){
            $m->setActived(0);
        }
        //Ensuite mettre le medias trouve en question a active = 1
        $medias->setActived(1);
        $em->flush();
        return $this->redirectToRoute('post_index');
    }

    /**
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/in/dossier/{id}", name="add_media_dossier")
     * @Method("GET")
     */
    public function AddMediaInFrontDossierAction(Media $media)
    {
        $em = $this->getDoctrine()->getManager();
        $medias = $em->getRepository('LabsAdminBundle:Media')->findOneMedia($media);
        if(!$media){
            throw $this->createNotFoundException('Le media photo ou image n\'existe pas');
        }
        $medias->setActived(1);
        $em->flush();
        return $this->redirectToRoute('dossier_index');
    }
}