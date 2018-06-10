<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Post;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class PostController
 * @package Labs\AdminBundle\Controller
 * @Route("/articles")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_index")
     * @Method({"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $posts = $this->getAllPosts();
        return $this->render('LabsAdminBundle:Posts:index.html.twig',[
            'posts' => $posts
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \LogicException
     * @Route("/create", name="post_create")
     * @Method({"GET","POST"})
     */
    public function createAction()
    {
        $user = $this->getUser();
        $post = new Post();
        $draft = $this->get('draft_create')->DraftCreate($user, $post);
        return $this->redirectToRoute('post_draft', ['id' => $draft->getId(), 'user_id' => $user->getId()]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @Route("/{id}/{user_id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $datas = $em->getRepository('LabsAdminBundle:Post')->getArticles($post, $user);
        if( null === $datas)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à modifier l'articles d'un utilisateur");
        }

        // Upload Medias
        if($request->isXmlHttpRequest()){
            $response = [];
            $media = $this->uploadMedia($request, $datas);
            if(null !== $media){
                $response = [
                    'results' => 'true',
                    'media'   => $media
                ];
                return new JsonResponse($response);
            }
            $response = ['results' => 'false'];
            return new JsonResponse($response);
        }

        $form = $this->createForm(PostType::class, $datas);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $datas->setDraft(1);
            $em->persist($datas);
            $em->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('LabsAdminBundle:Posts:edit_page.html.twig', [
            'form' => $form->createView(),
            'post' => $datas
        ]);

    }

    /**
     * @Route("/{id}/{user_id}/draft", name="post_draft")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Post $post
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \OutOfBoundsException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     */
    public function postDraftAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $datas = $this->get('draft_create')->DraftCreate($user, $post);
        if( null === $datas)
        {
            throw new NotFoundHttpException('Article introuvable');
        }
        // Upload Medias
        if($request->isXmlHttpRequest()){
            $response = [];
            $media = $this->uploadMedia($request, $datas);
            if(null !== $media){
                $response = [
                    'results' => 'true',
                    'media'   => $media
                ];
                return new JsonResponse($response);
            }
            $response = ['results' => 'false'];
            return new JsonResponse($response);
        }

        $form = $this->createForm(PostType::class, $datas);
        $form->add('draft', SubmitType::class, array(
            'label' => 'Enregistrer comme brouillon',
            'attr'  => array('class' => 'btn btn-danger')
        ));

        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){

            if ($form->get('draft')->isClicked()){
                $datas->setDraft(-1);
                $datas->setOnline(0);
            } else {
                $datas->setDraft(1);
            }

            $em->persist($datas);
            $em->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('LabsAdminBundle:Posts:draft_page.html.twig', [
            'form' => $form->createView(),
            'post' => $datas
        ]);
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="post_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('LabsAdminBundle:Post')->find($post);
        if( null === $posts)
            throw new NotFoundHttpException('element '.$posts.' n\'existe pas');
        else
            $em->remove($posts);
        $em->flush();
        return $this->redirectToRoute('post_index');
    }

    /**
     * @return array
     */
    private function getAllPosts()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LabsAdminBundle:Post')->getAll();
        if(null === $entity){
            throw new NotFoundHttpException('Entity introuvable');
        }
        return $entity;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function uploadMedia(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $media = new Media();
        $article = $em->getRepository('LabsAdminBundle:Post')->getCurrentPost($post);
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('file');
        $fileName = $article->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('gallery_directory'),
            $fileName
        );
        $media->setUrl($fileName);
        $media->setPost($article);
        $em->persist($media);
        $em->flush($media);
        return $media->getId();
    }

}