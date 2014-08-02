<?php

namespace GNKWLDF\LdfcorpBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PokemonRepository
 *
 * Pokémon Repository class
 */
class PokemonRepository extends EntityRepository
{
    
    /**
     * Check if there is one or more Entity in Pokemon Repository
     *
     * @return boolean
     */
    public function isPopulated()
    {
        return ($this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult()) > 0
        ;
    }
    
    /**
     * Find all Pokémon ordered by
     * @param string $order
     */
    public function findAllOrderBy($order)
    {
        return $this->findBy(array(), $order);
    }
    
    /**
     * Find a Pokémon using number
     *
     * @return Pokemon|null
     */
    public function findByNumber($number)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.number = :n')
            ->setParameter('n' , $number)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    /**
     * Find all active Pokémon ordered by number
     *
     * @return array
     */
    public function findAllActiveByNumber()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.active = :a')
            ->orderBy('p.number', 'ASC')
            ->setParameter('a', true)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Find all active Pokémon ordered by number
     *
     * @return array
     */
    public function findAllActiveByVote()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.active = :a')
            ->addOrderBy('p.vote', 'DESC')
            ->addOrderBy('p.number', 'ASC')
            ->setParameter('a', true)
            ->getQuery()
            ->getResult()
        ;
    }
}
