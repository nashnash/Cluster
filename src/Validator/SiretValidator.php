<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;

class SiretValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Siret) {
            throw new UnexpectedTypeException($constraint, Siret::class);
        }

        $valid = true;
        $siret = trim($value);
        $sum = 0;

        if (empty($siret) || strlen($siret) != 14) {
            $valid = false;
        }

        if (strlen($siret) === 14) {
            for ($i = 0; $i < 14; $i++) {
                if ($i % 2 == 0) {
                    $tmp = $siret[$i] * 2;
                    $tmp = $tmp > 9 ? $tmp - 9 : $tmp;
                } else {
                    $tmp = $siret[$i];
                }
                $sum += $tmp;
            }
        }

        if ($sum % 10 !== 0 && $sum > 0) {
            $valid = false;
        }


        if (!$valid) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ siret }}', $value)
                ->addViolation();
        }
    }
}