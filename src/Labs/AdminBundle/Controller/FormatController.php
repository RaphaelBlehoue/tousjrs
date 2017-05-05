<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Format;
use Labs\AdminBundle\Entity\Media;
use Labs\AdminBundle\Form\FormatType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Class FormatController
 * @package Labs\AdminBundle\Controller
 * @Route("/dossiers")
 */
class FormatController extends Controller
{
    /**
     * @Route("/", name="dossier_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $dossiers = $this->getAllDossiers();
        return $this->render('LabsAdminBundle:Dossiers:index.html.twig',[
            'dossiers' => $dossiers
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="dossier_create")
     * @Method({"GET","POST"})
     */
    public function createAction()
    {
        $user = $this->getUser();
        $format = new Format();
        $draft = $this->get('draft_create')->DraftCreate($user, $format);
        return $this->redirectToRoute('dossier_draft', ['id' => $draft->getId(), 'user_id' => $user->getId()]);
    }


    /**
     * @param Request $request
     * @param Format $format
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @Route("/{id}/{user_id}/edit", name="dossier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $datas = $em->getRepository('LabsAdminBundle:Format')->getFormatsArticles($format, $user);
        if( null === $datas)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à modifier le dossier d'un utilisateur");
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

        $form = $this->createForm(FormatType::class, $datas);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $datas->setDraft(1);
            $em->persist($datas);
            $em->flush();
            return $this->redirectToRoute('dossier_index');
        }
        return $this->render('LabsAdminBundle:Dossiers:edit_page.html.twig', [
            'form' => $form->createView(),
            'format' => $datas
        ]);

    }

    /**
     * @Route("/{id}/{user_id}/draft", name="dossier_draft")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Format $format
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \OutOfBoundsException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function FormatDraftAction(Request $request, Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $datas = $this->get('draft_create')->DraftCreate($user, $format);
        if( null === $datas)
        {
            throw new NotFoundHttpException('Dossier introuvable');
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

        $form = $this->createForm(FormatType::class, $datas);
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
            return $this->redirectToRoute('dossier_index');
        }
        return $this->render('LabsAdminBundle:Dossiers:draft_page.html.twig', [
            'form' => $form->createView(),
            'dossier' => $datas
        ]);
    }

    /**
     * @param Format $format
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="dossier_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $formats = $em->getRepository('LabsAdminBundle:Format')->find($format);
        if( null === $formats)
            throw new NotFoundHttpException('element '.$formats.' n\'existe pas');
        else
            $em->remove($formats);
        $em->flush();
        return $this->redirectToRoute('dossier_index');
    }

    /**
     * @return array
     */
    private function getAllDossiers()
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('LabsAdminBundle:Format')->getAll();
      if(null === $entity){
          throw new NotFoundHttpException('Entity introuvable');
      }
      return $entity;
    }


    /**
     * @param Request $request
     * @param Format $format
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function uploadMedia(Request $request, Format $format)
    {
        $em = $this->getDoctrine()->getManager();
        $media = new Media();
        $dossier = $em->getRepository('LabsAdminBundle:Format')->getFormats($format);
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('file');
        $fileName = $dossier->getSlug().'_'.md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('gallery_directory'),
            $fileName
        );
        $media->setUrl($fileName);
        $media->setFormat($dossier);
        $em->persist($media);
        $em->flush($media);
        return $media->getId();
    }


}
