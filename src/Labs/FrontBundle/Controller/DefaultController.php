<?php

namespace Labs\FrontBundle\Controller;

use Labs\AdminBundle\Entity\Format;
use Labs\AdminBundle\Entity\Item;
use Labs\AdminBundle\Entity\Post;
use Labs\AdminBundle\Entity\Section;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     * Page Home
     * Recupère les derniers arcticles dans la base de données et les inclus dans recents
     * Récupère toutes les Rubriques du Site et les met dans un tableau
     */
    public function getPageHomeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recent = $em->getRepository('LabsAdminBundle:Post')->getLastAricles(21);
        $sections = $this->FindBySection();
        return $this->render('LabsFrontBundle:Default:index.html.twig',[
            'recent' => $recent,
            'sections' => $sections
        ]);
    }

    /**
     * @param $max
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPageFooterAction($max)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('LabsAdminBundle:Post')->getLastAricles($max);
        $dossiers = $em->getRepository('LabsAdminBundle:Format')->findFormatNum($max);
        $sections = $em->getRepository('LabsAdminBundle:Section')->getAll();
        return $this->render('LabsFrontBundle:Includes/v1:footer.html.twig',[
            'last_dossiers' => $dossiers,
            'last_articles' => $articles,
            'menu_sections' => $sections
        ]);
    }


    /**
     * @param Section $section
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/tj/{slug}", name="front_section_page")
     * @Method({"GET"})
     * Page Rubrique
     * Recupère toute les sous-rubriques de la rubrique
     * Pour chaque sous-rubrique de la Rubrique on recupère 9 sous articles
     * Requete longue et lourd a Optimiser (*)
     */
    public function getPageSectionAction(Section $section)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionAllPosts($section);
        $heading = $em->getRepository('LabsAdminBundle:Section')->getOneSectionsAndItems($section);
        if(null === $sections || null === $heading){
            throw new NotFoundHttpException('Page introuvable');
        }
        $lastpost = $this->getPostByItemsInSection($sections);
        return $this->render('LabsFrontBundle:Sections:page_section.html.twig',[
            'sections' => $sections,
            'heading' => $heading,
            'lastpost' => $lastpost
        ]);
    }

    /**
     * @param Request $request
     * @param Item $item
     * @param $rubrique
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \LogicException
     * @Route("/{rubrique}/p/{slug}/page-{page}", name="front_item_page", requirements={"id" = "\d+"}, defaults={"page" = 1})
     * @Method({"GET"})
     * Page Sous-Rubrique
     */
    public function getPageItemAction(Request $request,Item $item, $rubrique, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $heading = $em->getRepository('LabsAdminBundle:Section')->getOneSectionsAndItemsBySlug($rubrique);
        $Items = $em->getRepository('LabsAdminBundle:Item')->getCurrentItem($item);
        if( null === $heading || null === $Items){
            throw new NotFoundHttpException('Page introuvable');
        }
        $findPost = $em->getRepository('LabsAdminBundle:Post')->getCountPostByItems($Items, 150);
        $posts = $this->get('knp_paginator')->paginate(
            $findPost,
            $request->request->getInt('page', $page), 12);
        $currentItem = $Items->getId();
        return $this->render('LabsFrontBundle:Items:page_item.html.twig',[
            'heading' => $heading,
            'currentId' => $currentItem,
            'item'    => $Items,
            'posts'   => $posts
        ]);

    }


    /**
     * @param Post $post
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/articles/{slug}", name="page_view")
     * @Method({"GET"})
     */
    public function getPageArticleAction(Post $post, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('LabsAdminBundle:Post')->getPostSlug($post, $slug);
        $old = $em->getRepository('LabsAdminBundle:Post')->OldPost($article->getId(), 3);
        return $this->render('LabsFrontBundle:Default:page_view.html.twig',[
            'article' => $article,
            'old'     => $old
        ]);
    }


    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @Route("pages/videos/page-{page}", name="videos_page", defaults={"page" = 1})
     * @Method({"GET"})
     * Page Video
     * Page qui liste toute les videos du site
     */
    public function videoListAction(Request $request, $page)
    {
        $api = $this->get('youtube_api.service');
        $getYoutube = $api->getSearchVideo(50);
        $youtube = $this->get('knp_paginator')->paginate(
            $getYoutube,
            $request->request->getAlnum('page', $page), 16);
        return $this->render('LabsFrontBundle:Videos:index.html.twig',[
            'youtube' => $youtube
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/videos/watch/{id}", name="video_view")
     * Page Pour visionner une video et afficher d'autre video
     */
    public function videoWatchViewAction($id)
    {
        $api = $this->get('youtube_api.service');
        $video = [];
        $video_array = $api->getVideoById($id);
        foreach ($video_array as $k => $v){
            $video[$k] = $v;
        }
        $youtube= $api->getSearchVideo(20);
        return $this->render('LabsFrontBundle:Videos:view.html.twig',[
            'video' => $video,
            'medias' => $youtube
        ]);
    }

    /**
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dossiers/index_page_{page}", name="dossier_page", defaults={"page" = 1})
     * @Method({"GET"})
     */
    public function getPageDossierAction(Request $request , $page)
    {
        $em = $this->getDoctrine()->getManager();
        $findossiers = $em->getRepository('LabsAdminBundle:Format')->findFormatNum(100);
        $dossiers = $this->get('knp_paginator')->paginate(
            $findossiers,
            $request->request->getInt('page', $page), 6);
        return $this->render('LabsFrontBundle:Dossiers:index.html.twig',[
            'dossiers' => $dossiers
        ]);
    }

    /**
     * @param Format $format
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("format/page/{rubrique}/{slug}", name="page_dossier_view")
     * @Method({"GET"})
     */
    public function getPageDossierView(Format $format, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = $em->getRepository('LabsAdminBundle:Format')->getDossierSlug($format, $slug);
        $old = $em->getRepository('LabsAdminBundle:Format')->OldDossier($dossier->getId(), 8);
        return $this->render('LabsFrontBundle:Dossiers:dossier_view_page.html.twig',[
            'article' => $dossier,
            'old'     => $old
        ]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response Recupere les Rubriques et les sous-rubriques associées
     * Recupere les Rubriques et les sous-rubriques associées
     * @Route("/ajax_menu_get", options={"expose"=true}, name="ajax_menu_get")
     * @Method("GET")
     */
    public function getMenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionsAndItems();
        if(null === $sections){
            throw  new NotFoundHttpException('Element introuvable');
        }
        if($request->isXmlHttpRequest())
        {
            $section = [];
            foreach ($sections as $s){
                $section[] = [
                    'id'    => $s->getId(),
                    'name'  => $s->getName(),
                    'color' => $s->getColor(),
                    'slug'  => $s->getSlug(),
                    'url'   => $this->generateUrl('front_section_page', ['slug' => $s->getSlug()])
                ];
            }
            return new JsonResponse(
                ['sections' => $section],
                200,
                ['Access-Control-Allow-Origin','*']
            );
        }
        return $this->render('LabsFrontBundle:Includes:header.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * Recupere les Rubriques et les sous-rubriques associées pour le menu responsive
     */
    public function getMenuResponsiveAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('LabsAdminBundle:Section')->getSectionsAndItems();
        if(null === $sections){
            throw  new NotFoundHttpException('Element introuvable');
        }
        return $this->render('LabsFrontBundle:Includes:responsive-header.html.twig',[
            'sections' => $sections
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * Recupere les recents articles du site
     */
    public function getRecentArticleAction(){
        $em = $this->getDoctrine()->getManager();
        $recents = $em->getRepository('LabsAdminBundle:Post')->getLastAricles(4);
        return $this->render('LabsFrontBundle:Includes:recent.html.twig',
            ['recents' => $recents]
        );

    }


    /**
     * @param $template
     * @param $max
     * @return \Symfony\Component\HttpFoundation\Response Recupere tout les flash informations ($max) dernières et les envoi à un include ($template)
     */
    public function getflashInfosAction($template, $max){
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('LabsAdminBundle:Info')->findInfoNum($max);
        if(null === $news){
            throw new NotFoundHttpException('Element introuvable');
        }
        return $this->render('LabsFrontBundle:Includes/v1:'.$template.'',
            ['news' => $news]
        );
    }

    /**
     * @param $item
     * @param $max
     * @return \Symfony\Component\HttpFoundation\Response Recupère les 9 derniers articles organiser par date de creation pour chaque Items
     * Recupère les 9 derniers articles organiser par date de creation pour chaque Items
     */
    public function getArticleByItemsAction($item, $max)
    {
        $articles = $this->findPostItem($item, $max);
        return $this->render('LabsFrontBundle:Includes:v1/posts_items.html.twig',
            ['articles' => $articles]
        );
    }
    
    /**
     * @param $section
     * @return \Symfony\Component\HttpFoundation\Response
     * Recupere les 9 derniere publication des items appartenant à la section
     */
    public function getArticleBySectionsAction($section)
    {
        $articles = $this->findPostSection($section, 11);
        return $this->render('LabsFrontBundle:Includes/v1:articles.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * @param $template
     * @param $max
     * @return \Symfony\Component\HttpFoundation\Response Recupère les dossiers avec une limit
     * Recupère les dossiers avec une limit
     */
    public function getDossierLimitAction($template, $max)
    {
        $em = $this->getDoctrine()->getManager();
        $dossiers = $em->getRepository('LabsAdminBundle:Format')->findFormatNum($max);
        return $this->render('LabsFrontBundle:Includes/v1:'.$template.'',[
            'dossiers' => $dossiers
        ]);
        
    }
    
    

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * Recupère les 3 dernières videos de la chaines youtube
     */
    public function getVideosReportAction()
    {
        $api = $this->get('youtube_api.service');
        $youtube = $api->getSearchVideo(3);
        return $this->render('LabsFrontBundle:Includes/v1:recent_video.html.twig',[
            'youtube' => $youtube
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * Recupère les 3 dernnières videos de la chaine youtube pour la sidebar
     */
    public function getSideBarvideoAction()
    {
        $api = $this->get('youtube_api.service');
        $youtube = $api->getSearchVideo(3);
        return $this->render('LabsFrontBundle:Includes/v1:video.html.twig',[
            'youtube' => $youtube
        ]);
    }

    /**
     * @return array
     * Recupère toute les Rubriques du site
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
     * @param $max
     * @return array
     * Recupère les publications de chaque sous-rubrique de la rubrique en param
     */
    private function findPostSection($options , $max = 9)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsAdminBundle:Post')->findPostItemBySection($options, $max);
        return $posts;
    }

    /**
     * @param $options
     * @param $max
     * @return array Recupère les rubriques les derniers posts par date des differents Items
     * Recupère les rubriques les derniers posts par date des differents Items
     */
    private function findPostItem($options, $max)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsAdminBundle:Post')->getCountPostByItems($options, $max);
        return $posts;
    }

    /**
     * @param $section
     * @return array
     */
    private function getPostByItemsInSection($section)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsAdminBundle:Post')->getPostByItems($section);
        return $posts;
    }
}
