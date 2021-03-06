<?php

namespace GNKWLDF\LdfcorpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use GNKWLDF\LdfcorpBundle\Form\PollType;
use GNKWLDF\LdfcorpBundle\Entity\Poll;
use Doctrine\Common\Collections\ArrayCollection;
use Gnuk\IPSecurity;

class PollController extends Controller
{
    
    /**
     * @Template()
     */
    public function voteIncludeAction($embeded = false)
    {
        $poll = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Poll')->findLastActive();
        return array('poll' => $poll);
    }

    /**
     * @Route("/poll/vote/{id}", name="ldfcorp_poll_vote", options={"expose"=true})
     * @Method({"POST"})
     */
    public function voteAction(Request $request, $id)
    {
        $number = $request->get('entry');
        if(null === $number)
        {
            throw new HttpException(400 ,'Entry parameter is missing');
            
        }
        
        $poll = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Poll')->find($id);
        $votingEntry = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:VotingEntry')->findFromPoll($id, $number);
        if(null === $poll)
        {
            throw $this->createNotFoundException('Poll not found');
        }
        
        if(null === $votingEntry)
        {
            throw $this->createNotFoundException('Entry not found');
        }
        if(!$poll->getActive())
        {
            throw new HttpException(400 ,'This poll is not active now');
        }
        
        $ipSecurity = new IPSecurity('poll-'.$id);
        $ipSecurity->setLimit(Poll::LIMIT_ANONYMOUS);
        $timeout = Poll::TIMEOUT_ANONYMOUS;

        $user = $this->getUser();
        if($user !== null)
        {
            $timeout = Poll::TIMEOUT_USER;
            $ipSecurity->setLimit(Poll::LIMIT_USER);
            $ipSecurity->setExtraInformation('username', $user->getUsername());
            $ipSecurity->setExtraInformation('email', $user->getEmail());
        }

        $timeoutMessage = $ipSecurity->timeout($timeout);
        
        if($timeoutMessage === IPSecurity::SUCCESS)
        {
            $ipSecurity->update();
        }
        else
        {
            if($timeoutMessage === IPSecurity::TIMEOUT)
            {
                throw new HttpException(405 ,'Please don\'t cheat, i\'m updating your timeout');
            }
            if($timeoutMessage === IPSecurity::LIMITED)
            {
                throw new HttpException(403 ,'You can\'t vote anymore this day');
            }
            throw new HttpException(405 ,'IPSecurity message : '.$timeoutMessage);
        }
        $votingEntry->incrementVote();
        $this->getDoctrine()->getManager()->flush();
        return new Response('OK');
    }

    /**
     * @Route("/poll/results/{id}", name="ldfcorp_poll_results")
     * @Method({"GET"})
     * @Template()
     */
    public function resultsAction($id)
    {
        $list = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:VotingEntry')->findAllByVoteFromPoll($id);
        return array('list' => $list);
    }

    /**
     * @Route("/admin/poll/create", name="ldfcorp_admin_poll_create")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function adminCreateAction(Request $request)
    {
        $poll = new Poll();
        $form = $this->createForm(new PollType(), $poll, array(
            'action' => $this->generateUrl('ldfcorp_admin_poll_create')
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($poll);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_admin_poll_list'));
        }
        
        return array(
            'pageTitle' => $this->get('translator')->trans('ldfcorp.admin.poll.create.title'),
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/polls", name="ldfcorp_admin_poll_list")
     * @Method({"GET"})
     * @Template()
     */
    public function adminListAction()
    {
        $pollRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Poll');
        $polls = $pollRepository->findAll();
        return array(
            'polls' => $polls
        );
    }
    
    /**
     * @Route("/admin/poll/edit/{id}", name="ldfcorp_admin_poll_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $pollRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Poll');
        $poll = $pollRepository->find($id);
        
        if(null === $poll)
        {
            throw $this->createNotFoundException();
        }

        $originalVotingEntries = new ArrayCollection();
        
        foreach ($poll->getVotingEntries() as $votingEntry) {
            $originalVotingEntries->add($votingEntry);
        }
        
        $form = $this->createForm(new PollType(), $poll, array(
            'action' => $this->generateUrl('ldfcorp_admin_poll_edit', array('id' => $id))
        ));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($originalVotingEntries as $votingEntry) {
                if ($poll->getVotingEntries()->contains($votingEntry) == false) {
                    $em->remove($votingEntry);
                }
            }
            $em->persist($poll);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_admin_poll_list'));
        }
        
        return $this->render(
            'GNKWLDFLdfcorpBundle:Poll:adminCreate.html.twig',
            array(
                'pageTitle' => $this->get('translator')->trans('ldfcorp.admin.poll.edit.title'),
                'form' => $form->createView()
            )
        );
    }
    
    /**
     * @Route("/admin/poll/delete/{id}", name="ldfcorp_admin_poll_delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function adminDeleteAction(Request $request, $id)
    {
        $pollRepository = $this->getDoctrine()->getRepository('GNKWLDFLdfcorpBundle:Poll');
        $poll = $pollRepository->find($id);
        
        if(null === $poll)
        {
            throw $this->createNotFoundException();
        }
        
        if($request->request->has('delete')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($poll);
            $em->flush();
            return $this->redirect($this->generateUrl('ldfcorp_admin_poll_list'));
        }
        else if($request->request->has('cancel')){
            return $this->redirect($this->generateUrl('ldfcorp_admin_poll_list'));
        }
        return array(
            'poll' => $poll
        );
    }
}