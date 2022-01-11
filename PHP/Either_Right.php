<?php

namespace App\Utils\FunctionalProgramming;

class Either_Right extends Either
{
    public function either(callable /*x -> a*/ $leftCase, callable /*y -> a*/ $rightCase)/*: a*/ {return $rightCase($this->value);}
}
