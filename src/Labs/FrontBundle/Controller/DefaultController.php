<?php

namespace Labs\FrontBundle\Controller;

use Labs\AdminBundle\Entity\Item;
use Labs\AdminBundle\Entity\Section;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('LabsFrontBundle:Default:index.html.twig');
    }

    /**
     * @param Section $section
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/{slug}", name="section_front_view")
     * @Method({"GET"})
     */
    public function ViewSectionAction(Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionAllPosts($section);
        return $this->render('LabsFrontBundle:Sections:view_section.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/{rubrique}/{slug}", name="item_front_view")
     * @Method({"GET"})
     */
    public function ViewItemAction(Item $item)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('LabsAdminBundle:Item')->getItemAllPosts($item);
        return $this->render('LabsFrontBundle:Items:view_item.html.twig',[
            'items' => $items
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionsAndItems();
        return $this->render('LabsFrontBundle:Includes:header.html.twig',[
            'sections' => $sections
        ]);
    }
}
