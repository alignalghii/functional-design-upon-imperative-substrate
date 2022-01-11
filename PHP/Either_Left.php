<?php

namespace App\Utils\FunctionalProgramming;

class Either_Left extends Either
{
    public function either(callable /*x -> a*/ $leftCase, callable /*y -> a*/ $rightCase)/*: a*/ {return $leftCase($this->value);}
}
