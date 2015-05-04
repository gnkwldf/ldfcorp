<?php
/**
 * Created by IntelliJ IDEA.
 * User: anthony
 * Date: 04/05/15
 * Time: 21:30
 */

namespace GNKWLDF\LdfcorpBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IframeChat extends Constraint {
    public $message = 'La chaîne "%string%" ne correspond à aucun service de chat autorisé.';

    public function validatedBy()
    {
        return 'gnkw_iframe_chat_validator';
    }
}