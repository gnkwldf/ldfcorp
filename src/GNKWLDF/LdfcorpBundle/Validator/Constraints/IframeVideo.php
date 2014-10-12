<?php

namespace GNKWLDF\LdfcorpBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IframeVideo extends Constraint
{
    public $message = 'La chaîne "%string%" ne correspond à aucun service vidéo autorisé.';
    
    public function validatedBy()
    {
        return 'gnkw_iframe_validator';
    }
}