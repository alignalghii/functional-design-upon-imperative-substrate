<?php

namespace App\Utils\FunctionalProgramming;

class Maybe_Just extends Maybe
{
    private $value;

    public function __construct($value) {$this->value = $value;}

    public function maybe_exec(callable /*y*/ $nothingCase, callable /*x -> y*/ $justCase)/*: y*/ {return $justCase($this->value);}
}
