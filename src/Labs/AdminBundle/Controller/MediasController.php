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
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @Route("/set/{id}/{name}/status", options={"expose"=true},  name="set_media_status")
     * @Method("GET")
     * @throws \Exception
     */
    public function addStatusMediaActivedOrNotActived(Request $request, $id, $name)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest() && $request->isMethod('GET'))
        {
            $media = $em->getRepository('LabsAdminBundle:Media')->findOneMedia($id);
            $entity = null;
            if($name == 'Format'){
                $entity = $media->getFormat()->getId();
            }else{
                $entity = $media->getPost()->getId();
            }
            if($this->clearActivedMedia($entity, $name)){
                $media->setActived(1);
                $em->flush();
                $data = [
                    'response_post'  => $entity,
                    'response_media' => $media->getId(),
                    'status'         => 200,
                    'text_href'      => 'Détacher de la une',
                    'message'        => 'Le media a été mis en avant',
                    'className'      => 'btn-primary actived'
                ];
            }
        }
       return new JsonResponse($data, 200);
    }

    /**
     * @param $entity_name
     * @param $name
     * @return mixed
     * @throws \Exception
     * Clear Toutes les valeurs actived de l'entity media à 0
     */
    private function clearActivedMedia($entity_name, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = null;
        if($name == 'Format'){
            $entity = $em->getRepository('LabsAdminBundle:Format')->getMediaByFormatId($entity_name);
        }else{
            $entity = $em->getRepository('LabsAdminBundle:Post')->getMediaByPostId($entity_name);
        }
        $media_post = $entity;
        foreach ($media_post->getMedias() as $media){
            $media->setActived(0);
        }
        $em->flush();
        return true;
    }
}