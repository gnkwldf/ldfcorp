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
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_page_list'));
        }
        
        return array(
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
        return array(
            'page' => $page
        );
    }
}