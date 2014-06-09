<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="ldfcorp_admin_dashboard")
     * @Method({"GET"})
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }
    
    /**
     * @Route("/admin/pokemon", name="ldfcorp_admin_pokemonlist")
     * @Method({"GET"})
     * @Template()
     */
     public function pokemonListAction()
     {
        $list = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findAllOrderBy(array('number' => 'ASC'));
        return array('list' => $list);
     }
     
     
    
    /**
     * @Route("/admin/pokemon/{number}/active", name="ldfcorp_admin_pokemon_active", options={"expose"=true})
     * @Method({"POST"})
     */
     public function activePokemonAction(Request $request, $number)
     {
        $pokemon = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findByNumber($number);
        if(null === $pokemon)
        {
            throw $this->createNotFoundException('Pokémon not found');
        }
        if(null === $request->get('active'))
        {
            throw new HttpException(400 ,'Active parameter is missing');
            
        }
        $active = $request->get('active');
        switch ($active) {
            case 'true':
                $pokemon->setActive(true);
                break;
            case 'false':
                $pokemon->setActive(false);
                break;
            default:
                throw new HttpException(400, 'Bad active parameter, you should use true or false');
        }
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response('OK');
     }
}