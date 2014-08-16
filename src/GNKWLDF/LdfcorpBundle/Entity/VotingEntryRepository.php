<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VotingEntryRepository
 *
 * VotingEntry Repository class
 */
class VotingEntryRepository extends EntityRepository
{
    
    /**
     * Find all active VotingEntries ordered by number
     *
     * @param integer $id poll_id 
     * @return array
     */
    public function findAllByVoteFromPoll($id)
    {
        return $this->createQueryBuilder('ve')
            ->select('ve')
            ->addOrderBy('ve.vote', 'DESC')
            ->addOrderBy('ve.name', 'ASC')
            ->where('ve.poll = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Return One VotingEntry if it's in the Poll
     * @param integer $pollId
     * @param integer $id
     */
    public function findFromPoll($pollId, $id)
    {
        return $this->createQueryBuilder('ve')
            ->select('ve')
            ->where('ve.id = :id')
            ->andWhere('ve.poll = :pid')
            ->setParameter('pid', $pollId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}