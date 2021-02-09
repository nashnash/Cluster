<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Siret
 * @package App\Validator
 */
class Siren extends Constraint
{

    public $message = 'The SIREN "{{ siren }}" contains an illegal characters. Please respect the format';

}