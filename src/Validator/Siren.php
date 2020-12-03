<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Siret
 * @package App\Validator
 */
class Siren extends Constraint
{

    public $message = 'The SIRET "{{ siren }}" contains an illegal characters. Please respect the format';

}