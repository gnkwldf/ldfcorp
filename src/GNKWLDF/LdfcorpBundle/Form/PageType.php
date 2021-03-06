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
            ->add('videoLink', null, array(
                'label' => 'ldfcorp.page.form.video.link',
                'attr' => array(
                    'class' => 'ldfcorp-form-link-video-iframe'
                )
            ))
            ->add('chatLink', null, array(
                'label' => 'ldfcorp.page.form.chat.link',
                'attr' => array(
                    'class' => 'ldfcorp-form-link-chat-iframe'
                )
            ))
            ->add('description', null, array(
                'label' => 'ldfcorp.page.form.description'
            ))
            ->add('online', null, array(
                'label' => 'ldfcorp.page.form.online',
                'required'  => false
            ))
            ->add('ads', null, array(
                'label' => 'ldfcorp.page.form.ads',
                'required'  => false
            ))
            ->add('links', 'collection', array(
                'type' => new PageLinkType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'ldfcorp.page.form.links'
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
