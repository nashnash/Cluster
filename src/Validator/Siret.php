<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Siret
 * @package App\Validator
 */
class Siret extends Constraint
{

    public $message = 'The SIRET "{{ siret }}" contains an illegal characters. Please respect the format';

}