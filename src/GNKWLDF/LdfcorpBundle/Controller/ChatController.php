<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="ldfcorp_chat")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    /**
     * @Route("/api/chat/send", name="ldfcorp_api_chat_send", options={"expose"=true})
     * @Method({"POST"})
     */
    public function sendAction(Request $request)
    {
        $content = $request->getContent();
        $json = json_decode($content, true);
        $user = $this->getUser();
        if(null === $user)
        {
            throw  new HttpException(403);
        }
        if(!(isset($json["message"]) AND is_string($json["message"])))
        {
            throw new HttpException(400 ,'Message parameter is missing');
        }
        $this->notifyAll('message', 
            array(
                "username" => $user->getUsername(),
                "message" => $json["message"]
            )
        );
        return new Response(json_encode(array(
            "confirmation" => "OK"
        )));
    }
    
    /**
     * @Route("/chat/js/main.js", name="ldfcorp_chat_js_main")
     * @Method({"GET"})
     */
    public function mainJsAction() {
        $params = array( 'host' => $this->container->getParameter('node_client'));
        $rendered = $this->renderView( 'GNKWLDFLdfcorpBundle:Chat:main.js.twig', $params );
        $response = new Response( $rendered );
        $response->headers->set( 'Content-Type', 'text/javascript' );
        return $response;
    }
    
    private function getElephant() 
    {
        return new Client(new Version1X($this->container->getParameter('node_server')));
    }
    
    public function notifyAll($type, $data)
    {
        $object = array(
            "broadcast" => true,
            "type" => $type,
            "data" => $data
        );
        $elephant = $this->getElephant();
        $elephant->initialize();
        $elephant->emit('chat', $object);
        $elephant->close();
    }
}