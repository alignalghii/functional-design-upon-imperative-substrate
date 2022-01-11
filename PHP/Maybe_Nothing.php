<?php

namespace App\Utils\FunctionalProgramming;

class Maybe_Nothing extends Maybe
{
    public function maybe_exec(callable /*y*/ $nothingCase, callable /*x -> y*/$justCase)/*: y*/ {return $nothingCase();}
}
