<?php

namespace Labs\AdminBundle\Controller;

use Labs\AdminBundle\Entity\Item;
use Labs\AdminBundle\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ItemController
 * @package Labs\AdminBundle\Controller
 * @Route("/items")
 */
class ItemController extends Controller
{
    /**
     * @Route("/", name="item_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $items = $this->getAllItem();
        return $this->render('LabsAdminBundle:Items:index.html.twig',[
            'items' => $items
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create", name="item_create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted())
        {
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute('item_index');
        }
        return $this->render('LabsAdminBundle:Items:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/edit", name="item_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Item $item)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('LabsAdminBundle:Item')->getOne($item);
        if(null === $items){
            throw new NotFoundHttpException ("L'element de id ".$item." n'existe pas");
        }
        $form = $this->createForm(ItemType::class, $items);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('item_index');
        }
        return $this->render('LabsAdminBundle:Items:edit.html.twig',array(
            'form' => $form->createView(),
            'id'   => $items->getId()
        ));
    }

    /**
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/delete", name="item_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Item $item)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('LabsAdminBundle:Item')->find($item);
        if( null === $items)
            throw new NotFoundHttpException('element '.$items.' n\'existe pas');
        else
            $em->remove($items);
        $em->flush();
        return $this->redirectToRoute('item_index');
    }

    /**
     * @return array
     */
    private function getAllItem()
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('LabsAdminBundle:Item')->getAll();
        return $items;
    }
}
