<?php

namespace GNKWLDF\LdfcorpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserFromAdminType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'label' => 'ldfcorp.user.form.username'
            ))
            ->add('email', null, array(
                'label' => 'ldfcorp.user.form.email'
            ))
            ->add('roles', 'collection', array(
                'type' => 'choice',
                'label' => 'ldfcorp.user.form.roles',
                'options' => array(
                    'choices' => array(
                        'ROLE_SUPER_ADMIN' => 'ldfcorp.user.form.role.admin',
                        'ROLE_VIP' => 'ldfcorp.user.form.role.vip',
                        'ROLE_USER' => 'ldfcorp.user.form.role.user'
                    )
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'ldfcorp.user.form.submit'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GNKWLDF\LdfcorpBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gnkwldf_ldfcorpbundle_userfromadmin';
    }
}
