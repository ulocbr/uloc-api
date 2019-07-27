<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CpfValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {


        if (!$this->verificaCPF($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    protected function verificaCPF($value){
        if (strlen($value) != 11)
            return false;
        $value = str_pad(preg_replace('/[^0-9]/', '', $value), 11, '0', STR_PAD_LEFT);
        if (strlen($value) != 11 || $value == '00000000000' || $value == '11111111111' || $value == '22222222222' || $value == '33333333333' || $value == '44444444444' || $value == '55555555555' || $value == '66666666666' || $value == '77777777777' || $value == '88888888888' || $value == '99999999999')
            return false;
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++)
                $d += $value{$c} * (($t + 1) - $c);
            $d = ((10 * $d) % 11) % 10;
            if ($value{$c} != $d)
                return false;
        }
        return true;
    }
}