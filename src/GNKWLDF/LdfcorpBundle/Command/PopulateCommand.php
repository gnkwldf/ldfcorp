<?php
namespace GNKWLDF\LdfcorpBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GNKWLDF\LdfcorpBundle\Entity\Pokemon;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ldfcorp:populate')
            ->setDescription('Populate Pokemon entities')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Database now populated';
        
        $doctrine = $this->getContainer()->get('doctrine');
        
        $populate = !($doctrine->getRepository('GNKWLDFLdfcorpBundle:Pokemon')->isPopulated());
        
        if($populate)
        {
            $this->populate($doctrine->getEntityManager());
        }
        else
        {
            $text = 'Database already populated';
        }
        
        
        $output->writeln($text);
    }
    
    /**
     * Populate Database
     */
    protected function populate($em)
    {
        for($i = 0; $i <= 719; $i++)
        {
            $pokemon = new Pokemon();
            $pokemon->setNumber($i);
            $em->persist($pokemon);
        }
        $em->flush();
    }
}