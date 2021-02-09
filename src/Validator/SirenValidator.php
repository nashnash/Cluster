<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;

class SirenValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Siren) {
            throw new UnexpectedTypeException($constraint, Siren::class);
        }
        $siren = str_replace(' ', '', $value);

        $total = 0;
        if(strlen($siren) === 9) {
            for ($i = 0; $i < 9; $i++) {
                $temp = substr($siren, $i, 1);
                if ($i % 2 == 1) {
                    $temp *= 2;
                    if ($temp > 9) {
                        $temp -= 9;
                    }
                }
                $total += $temp;
            }
        }

        if ((($total % 10) == 0) && strlen($siren) != 9 || !is_numeric($siren)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ siren }}', $value)
                ->addViolation();
        }
    }
}