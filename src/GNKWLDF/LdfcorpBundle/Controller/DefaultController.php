<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gnkw\Symfony\HttpFoundation\FormattedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use GNKWLDF\LdfcorpBundle\Entity\Page;
use GNKWLDF\LdfcorpBundle\Entity\PageLink;
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
        $page = $this->getIndexPage();
        return array('page' => $page);
    }
    
    /**
     * @Route("/api/index/page", name="ldfcorp_api_home_page", options={"expose"=true})
     * @Method({"GET"})
     */
    public function apiIndexPageAction()
    {
        $page = $this->getIndexPage();
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($page, 'json');
        return new FormattedResponse($json);
    }
    
    private function getIndexPage() {
        $page = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Page')->findOneByLastOnline();
        if($page === null)
        {
            $page = $this->getSystemDefaultPage();
        }
        return $page;
    }
    
    private function getSystemDefaultPage() {
        $json = array();
        $t = $this->get('translator');
        $filename = __DIR__ . '/../Resources/data/page_default.json';
        if(!is_file($filename)){
            throw $this->createNotFoundException('System default page not found');
        }
        $file = file_get_contents($filename);
        $json = json_decode($file, true);
        
        $serializer = $this->get('jms_serializer');
        $page = $serializer->deserialize($file, 'GNKWLDF\LdfcorpBundle\Entity\Page', 'json');
        $page->setName($t->trans($page->getName()));
        $page->setVideoLink($t->trans($page->getVideoLink()));
        $page->setDescription($t->trans($page->getDescription()));
        return $page;
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
     * @Route("/api/pokemon/results", name="ldfcorp_api_pokemon_results", options={"expose"=true})
     * @Method({"GET"})
     */
    public function apiResultsAction()
    {
        $t = $this->get('translator');
        $list = array();
        $listEntities = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findAllActiveByVote();
        foreach($listEntities AS $pokemon)
        {
            $element = array(
                'name' => $t->trans('ldfcorp.pokemon.list.' . $pokemon->getNumber() . '.name'),
                'number' => $pokemon->getNumber(),
                'vote' => $pokemon->getVote()
            );
            $list[] = $element;
        }
        
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($list, 'json');
        return new FormattedResponse($json);
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
        
        $ipSecurity = new IPSecurity('pokemon');

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
