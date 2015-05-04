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
use GNKWLDF\LdfcorpBundle\Entity\Poll;
use GNKWLDF\LdfcorpBundle\Entity\Pokemon;
use Doctrine\Common\Collections\ArrayCollection;
use Gnkw\Symfony\HttpFoundation\FormattedResponse;

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
        
            $this->changePage($page);
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
        $iframeChat = $this->container->getParameter('ldfcorp_iframe_chat');
        $pokemonTimeout = Pokemon::TIMEOUT_ANONYMOUS;
        $pollTimeout = Poll::TIMEOUT_ANONYMOUS;
        $user = $this->getUser();
        if($user !== null)
        {
            $pokemonTimeout = Pokemon::TIMEOUT_USER;
            $pollTimeout = Poll::TIMEOUT_USER;
        }
        $pageRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page');
        $page = $pageRepository->find($id);
        if(null == $page) {
            throw $this->createNotFoundException();
        }
        $pageChatLink = $page->getChatLink();
        if(!empty($pageChatLink)) {
            $iframeChat = $pageChatLink;
        }
        $actions = $this->havePageRights($user, $page);
        return array(
            'actions' => $actions,
            'page' => $page,
            'user' => $page->getUser(),
            'currentUser' => $user,
            'pokemonTimeout' => $pokemonTimeout,
            'pollTimeout' => $pollTimeout,
            'iframeChat' => $iframeChat
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
        
        if(!$this->havePageRights($this->getUser(), $page))
        {
            throw new HttpException(403);
        }
        
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('ldfcorp_page_edit', array('id' => $id))
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $this->changePage($page);
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
    
    private function changePage($page)
    {
        $originalLinks = new ArrayCollection();
        
        foreach ($page->getLinks() as $link) {
            $originalLinks->add($link);
        }
        
        $em = $this->getDoctrine()->getManager();
        
        if(null !== $page->getVideoLink()) {
            $checker = $this->get('gnkw_video_manager')->getChecker($page->getVideoLink());
            if(null !== $checker) {
                $page->setVideoLink($checker->getIframe());
            }
        }
        
        foreach ($originalLinks as $link) {
            if ($page->getLinks()->contains($link) == false) {
                $em->remove($link);
            }
        }
        $em->persist($page);
        $em->flush();
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
        
        if(!$this->havePageRights($this->getUser(), $page))
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
    
    private function havePageRights($user, $page) {
        if(null == $user) {
            return false;
        }
        return (
            $user->getId() === $page->getUser()->getId() ||
            $this->get('security.context')->isGranted('ROLE_ADMIN')
        );
    }
    
    /**
     * @Route("/page/online/{id}", name="ldfcorp_page_online", options={"expose"=true})
     * @Method({"POST"})
     */
    public function onlinePageAction(Request $request, $id)
    {
        $page = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page')->find($id);
        if(null === $page)
        {
            throw $this->createNotFoundException('Page not found');
        }
        if(null === $request->get('online'))
        {
            throw new HttpException(400 ,'Online parameter is missing');
            
        }
        if(!$this->havePageRights($this->getUser(), $page))
        {
            throw new HttpException(403);
        }
        $online = $request->get('online');
        switch ($online) {
            case 'true':
                $page->setOnline(true);
                break;
            case 'false':
                $page->setOnline(false);
                break;
            default:
                throw new HttpException(400, 'Bad online parameter, you should use true or false');
        }
        $this->getDoctrine()->getManager()->flush();
        return new Response('OK');
    }
    
    /**
     * @Route("/api/video/checker", name="ldfcorp_api_video_checker", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function apiVideoCheckerAction(Request $request)
    {
        return $this->apiIframeCheckerAction($request, 'gnkw_video_manager');
    }

    /**
     * @Route("/api/chat/checker", name="ldfcorp_api_chat_checker", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function apiChatCheckerAction(Request $request)
    {
        return $this->apiIframeCheckerAction($request, 'gnkw_chat_manager');
    }

    private function apiIframeCheckerAction(Request $request, $checkerName) {
        $content = $request->getContent();
        if(empty($content))
        {
            throw new HttpException(400, 'Bad parameters format');
        }
        $params = json_decode($content, true);
        if(!isset($params['url']))
        {
            throw new HttpException(400, '"url" parameter is missing');
        }

        $url = $params['url'];

        $checker = $this->get($checkerName)->getChecker($url);
        $iframe = array(
            "valid" => false,
            "changing" => false,
            "type" => null,
            "url" => null
        );
        if(null !== $checker)
        {
            $iframeUrl = $checker->getIframe();
            $iframe["valid"] = true;
            $iframe["type"] = $checker->getType();
            $iframe["url"] = $iframeUrl;
            if($url !== $iframeUrl) {
                $iframe["changing"] = true;
            }
        }
        return new FormattedResponse($iframe);
    }
}