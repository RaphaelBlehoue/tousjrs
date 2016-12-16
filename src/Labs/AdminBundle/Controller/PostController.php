<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Post;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     */
    public function indexAction()
    {
        $posts = $this->getAllPosts();
        return $this->render('LabsAdminBundle:Posts:index.html.twig',[
            'posts' => $posts
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="post_create")
     * @Method({"GET","POST"})
     */
    public function createAction()
    {
        $user = $this->getUser();
        $draft = $this->get('draft_create')->DraftCreate($user);
        return $this->redirectToRoute('post_edit', ['id' => $draft->getId()]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $em->getRepository('LabsAdminBundle:Post')->getPostForUser($this->getUser(), $post);
        if( null === $datas)
        {
            throw new NotFoundHttpException('Article introuvable');
        }
        // Upload Medias
        if($request->isXmlHttpRequest()){
            $response = [];
            if($this->uploadMedia($request, $datas)){
                $response = ['results' => 'true'];
            }else{
                $response = ['results' => 'false'];
            }
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
     */
    private function uploadMedia(Request $request, Post $post)
    {
            $em = $this->getDoctrine()->getManager();
            $media = new Media();
            $article = $em->getRepository('LabsAdminBundle:Post')->getArticles($post);
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $request->files->get('file');
            $fileName = $article->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->container->getParameter('gallery_directory'),
                $fileName
            );
            $media->setUrl($fileName);
            $media->setPost($article);
            $em->persist($media);
            $em->flush($media);
            return true;
    }
}
