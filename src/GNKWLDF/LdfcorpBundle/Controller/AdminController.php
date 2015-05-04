<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GNKWLDF\LdfcorpBundle\Form\UserFromAdminType;
use GNKWLDF\LdfcorpBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminController extends Controller
{
    const MAX_POKEMON = 5;

    /**
     * @Route("/admin/dashboard", name="ldfcorp_admin_dashboard")
     * @Method({"GET"})
     * @Template()
     */
    public function dashboardAction()
    {
        $pokemonList = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->findActiveByVote(self::MAX_POKEMON);
        return array(
            'pokemonList' => $pokemonList
        );
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
     * @Route("/admin/users", name="ldfcorp_admin_userlist")
     * @Method({"GET"})
     * @Template()
     */
    public function userListAction()
    {
        $list = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:User')->findBy(array(), array('username' => 'ASC'));
        return array('list' => $list);
    }
    
    
    /**
     * @Route("/admin/user/edit/{id}", name="ldfcorp_admin_user_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function userEditAction(Request $request, $id)
    {
        $userRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:User');
        $user = $userRepository->find($id);
        if(null === $user)
        {
            throw $this->createNotFoundException('User not found');
        }
        
        $form = $this->createForm(new UserFromAdminType(), $user, array(
            'action' => $this->generateUrl('ldfcorp_admin_user_edit', array('id' => $id))
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_admin_userlist'));
        }
        
        return array(
            'pageTitle' => $this->get('translator')->trans('ldfcorp.admin.user.edit.title'),
            'user' => $user,
            'form' => $form->createView()
        );
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
        $this->getDoctrine()->getManager()->flush();
        return new Response('OK');
    }
}