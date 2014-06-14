<?php

namespace GNKWLDF\LdfcorpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'ldfcorp.page.form.name'
            ))
            ->add('description', null, array(
                'label' => 'ldfcorp.page.form.description'
            ))
            ->add('save', 'submit', array(
                'label' => 'ldfcorp.page.create.submit'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GNKWLDF\LdfcorpBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gnkwldf_ldfcorpbundle_page';
    }
}
