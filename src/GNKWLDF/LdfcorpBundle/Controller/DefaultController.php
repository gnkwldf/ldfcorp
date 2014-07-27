<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Gnuk\IPSecurity;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="ldfcorp_home")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Template()
     */
    public function bootstrapHeaderAction($pageActive = null, $max = 3)
    {
        $pages = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page')->findByLastOnline($max);
        return array(
            'pages' => $pages,
            'pageActive' => $pageActive
        );
    }
    
    /**
     * @Route("/results", name="ldfcorp_results")
     * @Method({"GET"})
     * @Template()
     */
    public function resultsAction()
    {
        $list = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findAllActiveByVote();
        return array('list' => $list);
    }
    
    /**
     * @Route("/vote", name="ldfcorp_vote", options={"expose"=true})
     * @Method({"POST"})
     */
    public function voteAction(Request $request)
    {
        $number = $request->get('pokemon');
        if(null === $number)
        {
            throw new HttpException(400 ,'Pokémon parameter is missing');
            
        }
        
        $pokemon = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findByNumber($number);
        if(null === $pokemon)
        {
            throw $this->createNotFoundException('Pokémon not found');
        }
        if(!$pokemon->getActive())
        {
            throw new HttpException(400 ,'Pokémon is not active now');
        }
        
        $ipSecurity = new IPSecurity('test');

        $timeToWait = 5;

        if($ipSecurity->timeout($timeToWait))
        {
            $ipSecurity->update();
        }
        else {
            $ipSecurity->update();
            throw new HttpException(405 ,'Please don\'t cheat, i\'m updating your timeout');
        }
        $pokemon->incrementVote();
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response('OK');
    }
    
    /**
     * @Route("/vote/embed", name="ldfcorp_vote_embed")
     * @Method({"GET"})
     * @Template()
     */
    public function voteEmbedAction()
    {
        return array();
    }
    
    
    /**
     * @Template()
     */
    public function voteIncludeAction($embeded = false)
    {
        $list = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findAllActiveByNumber();
        return array('list' => $list, 'embeded' => $embeded);
    }
}
