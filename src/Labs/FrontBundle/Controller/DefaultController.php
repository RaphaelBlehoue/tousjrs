<?php

namespace Labs\FrontBundle\Controller;

use Labs\AdminBundle\Entity\Item;
use Labs\AdminBundle\Entity\Post;
use Labs\AdminBundle\Entity\Section;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recent = $em->getRepository('LabsAdminBundle:Post')->findArticleNum(15);
        $sections = $this->FindBySection();
        return $this->render('LabsFrontBundle:Default:index.html.twig',[
            'recent' => $recent,
            'sections' => $sections
        ]);
    }

    /**
     * @param $section
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticleBySectionsAction($section)
    {
        $articles = $this->findPostSection($section);
        return $this->render('LabsFrontBundle:Includes:articles.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionsAndItems();
        if(null === $sections){
            throw  new NotFoundHttpException('Element introuvable');
        }
        return $this->render('LabsFrontBundle:Includes:header.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @param Section $section
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tj/{slug}", name="front_section_page")
     * @Method({"GET"})
     */
    public function getPageSectionAction(Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionAllPosts($section);
        if(null === $sections){
            throw new NotFoundHttpException('Page introuvable');
        }
        return $this->render('LabsFrontBundle:Sections:view_section.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{rubrique}/p/{slug}", name="front_item_page")
     * @Method({"GET"})
     */
    public function getPageItemAction(Item $item)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('LabsAdminBundle:Item')->getItemAllPosts($item);
        if(null === $items){
            throw new NotFoundHttpException('Page introuvable');
        }
        return $this->render('LabsFrontBundle:Items:view_item.html.twig',[
            'items' => $items
        ]);
    }

    /**
     * @return array
     */
    protected function FindBySection()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getAll();
        $section_array = [];
        foreach ($sections as $s){
            $section_array[] = $s;
        }
        return $section_array;
    }

    /**
     * @param $options
     * @return array
     */
    private function findPostSection($options)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsAdminBundle:Post')->findPostItemBySection($options);
        return $posts;
    }
}
