<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Info;
use Labs\AdminBundle\Form\InfoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class InfoController
 * @package Labs\AdminBundle\Controller
 * @Route("/info")
 */
class InfoController extends Controller
{
    /**
     * @Route("/", name="info_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $infos = $this->getAllInfo();
        return $this->render('LabsAdminBundle:Infos:index.html.twig',[
            'infos' => $infos
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="info_create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $info = new Info();
        $form = $this->createForm(InfoType::class, $info);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted())
        {
            $em->persist($info);
            $em->flush();
            return $this->redirectToRoute('info_index');
        }
        return $this->render('LabsAdminBundle:Infos:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Info $info
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit/{slug}", name="info_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Info $info)
    {
        $em = $this->getDoctrine()->getManager();
        $infos = $em->getRepository('LabsAdminBundle:Info')->getOne($info);
        if(null === $infos){
            throw new NotFoundHttpException ("L'element de id ".$infos." n'existe pas");
        }
        $form = $this->createForm(InfoType::class, $infos);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('info_index');
        }
        return $this->render('LabsAdminBundle:Infos:edit.html.twig',array(
            'form' => $form->createView(),
            'info'   => $infos
        ));
    }

    /**
     * @param Info $info
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="info_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Info $info)
    {
        $em = $this->getDoctrine()->getManager();
        $infos = $em->getRepository('LabsAdminBundle:Info')->find($info);
        if( null === $infos)
            throw new NotFoundHttpException('element '.$infos.' n\'existe pas');
        else
            $em->remove($infos);
        $em->flush();
        return $this->redirectToRoute('info_index');
    }

    /**
     * @return array
     */
    private function getAllInfo()
    {
        $em = $this->getDoctrine()->getManager();
        $infos = $em->getRepository('LabsAdminBundle:Info')->getAll();
        return $infos;
    }
}
