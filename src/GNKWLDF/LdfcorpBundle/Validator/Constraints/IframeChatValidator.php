<?php
/**
 * Created by IntelliJ IDEA.
 * User: anthony
 * Date: 04/05/15
 * Time: 21:31
 */

namespace GNKWLDF\LdfcorpBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IframeChatValidator extends ConstraintValidator{
    /**
     * @var \GNKWLDF\LdfcorpBundle\Service\ChatManager
     */
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