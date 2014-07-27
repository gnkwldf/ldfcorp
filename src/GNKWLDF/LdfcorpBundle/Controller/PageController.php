<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use GNKWLDF\LdfcorpBundle\Form\PageType;
use GNKWLDF\LdfcorpBundle\Entity\Page;
use Doctrine\Common\Collections\ArrayCollection;

class PageController extends Controller
{
    /**
     * @Route("/page/create", name="ldfcorp_page_create")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function createAction(Request $request)
    {
        $page = new Page();
        $page->setUser($this->getUser());
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('ldfcorp_page_create')
        ));

        $originalLinks = new ArrayCollection();
        
        foreach ($page->getLinks() as $link) {
            $originalLinks->add($link);
        }
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
        
            $em = $this->getDoctrine()->getManager();
            foreach ($originalLinks as $link) {
                if ($page->getLinks()->contains($link) == false) {
                    $em->remove($link);
                }
            }
            $em->persist($page);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_page_list'));
        }
        
        return array(
            'pageTitle' => $this->get('translator')->trans('ldfcorp.page.create.title'),
            'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/page", name="ldfcorp_page_list")
     * @Method({"GET"})
     * @Template()
     */
    public function listAction()
    {
        $pages = $this->getUser()->getPages();
        return array(
            'pages' => $pages
        );
    }
    
    /**
     * @Route("/page/{id}", name="ldfcorp_page_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction($id)
    {
        $pageRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page');
        $page = $pageRepository->find($id);
        $currentUser = $this->getUser();
        $user = $page->getUser();
        $actions = false;
        if($currentUser !== null AND $currentUser->getId() == $user->getId()) {
            $actions = true;
        }
        return array(
            'actions' => $actions,
            'page' => $page,
            'user' => $page->getUser()
        );
    }
    
    /**
     * @Route("/page/edit/{id}", name="ldfcorp_page_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $pageRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page');
        $page = $pageRepository->find($id);
        
        if(null === $page)
        {
            throw $this->createNotFoundException();
        }
        
        if($this->getUser()->getId() !== $page->getUser()->getId())
        {
            throw new HttpException(403);
        }

        $originalLinks = new ArrayCollection();
        
        foreach ($page->getLinks() as $link) {
            $originalLinks->add($link);
        }
        
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('ldfcorp_page_edit', array('id' => $id))
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($originalLinks as $link) {
                if ($page->getLinks()->contains($link) == false) {
                    $em->remove($link);
                }
            }
            $em->persist($page);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_page_show', array('id' => $id)));
        }
        
        return $this->render(
            'GNKWLDFLdfcorpBundle:Page:create.html.twig',
            array(
                'pageTitle' => $this->get('translator')->trans('ldfcorp.page.edit.title'),
                'form' => $form->createView()
            )
        );
    }
    
    /**
     * @Route("/page/delete/{id}", name="ldfcorp_page_delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $pageRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page');
        $page = $pageRepository->find($id);
        
        if(null === $page)
        {
            throw $this->createNotFoundException();
        }
        
        if($this->getUser()->getId() !== $page->getUser()->getId())
        {
            throw new HttpException(403);
        }
        
        if($request->request->has('delete')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_page_list'));
        }
        else if($request->request->has('cancel')){
            return $this->redirect($this->generateUrl('ldfcorp_page_show', array('id' => $id)));
        }
        return array(
            'page' => $page
        );
    }
}