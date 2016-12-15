<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Section;
use Labs\AdminBundle\Form\SectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SectionController
 * @package Labs\AdminBundle\Controller
 * @Route("/sections")
 */
class SectionController extends Controller
{
    /**
     * @Route("/", name="section_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $sections = $this->getAllSection();
        return $this->render('LabsAdminBundle:Sections:index.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="section_create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted())
        {
            $em->persist($section);
            $em->flush();
            return $this->redirectToRoute('section_index');
        }
        return $this->render('LabsAdminBundle:Sections:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Section $section
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit/{slug}", name="section_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getOne($section);
        if(null === $sections){
            throw new NotFoundHttpException("L'element de id ".$section." n'existe pas");
        }
        $form = $this->createForm(SectionType::class, $sections);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('section_index');
        }
        return $this->render('LabsAdminBundle:Sections:edit.html.twig',array(
            'form' => $form->createView(),
            'section'   => $sections
        ));
    }

    /**
     * @param Section $section
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="section_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->find($section);
        if( null === $sections)
            throw new NotFoundHttpException('element '.$section.' n\'existe pas');
        else
            $em->remove($sections);
        $em->flush();
        return $this->redirectToRoute('section_index');
    }

    /**
     * @return array
     */
    private function getAllSection()
    {
      $em = $this->getDoctrine()->getManager();
      $section = $em->getRepository('LabsAdminBundle:Section')->getAll();
      return $section;
    }
}
