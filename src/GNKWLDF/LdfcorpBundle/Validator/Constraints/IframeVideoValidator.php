<?php

namespace GNKWLDF\LdfcorpBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IframeVideoValidator extends ConstraintValidator
{

    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint)
    {
        if(null !== $value) {
            $checker = $this->manager->getChecker($value);
            if(null === $checker) {
                $this->context->addViolation(
                    $constraint->message,
                    array('%string%' => $value)
                );
            }
        }
    }
}