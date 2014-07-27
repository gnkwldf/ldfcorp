<?php

namespace GNKWLDF\LdfcorpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserLinkType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'ldfcorp.user.link.name'
            ))
            ->add('title', null, array(
                'label' => 'ldfcorp.user.link.title'
            ))
            ->add('url', null, array(
                'label' => 'ldfcorp.user.link.url'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GNKWLDF\LdfcorpBundle\Entity\UserLink'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gnkwldf_ldfcorpbundle_userlink';
    }
}
