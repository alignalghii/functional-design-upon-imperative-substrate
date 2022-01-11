<?php

namespace App\Utils\FunctionalProgramming;

/**
 * Exactly corresponding to Haskell's `Monad` type-class
 * Haskell's type-classes can be grasped in PHP as traits.
 *
 * self  is regarded as the actual Monad instance, partially supported by PHP
 * $this is regareded as having type: self of x
 */
trait Monad
{
    /**
     * Each Monad instance must implement these functions:
     */

    public abstract static function unit(): self;
    public abstract        function map(callable $f/*: x -> y*/): self /*of y*/;
    public abstract        function join(): self;

    /**
     * Monad factors these function definitions out
     * as common for any Monad instance:
     */

    public function bind(callable /*x -> self of y*/ $mf): self /* of y*/
    {
        return $this->map($mf)->join();
    }

    public function liftM2($mY, callable /*(x, y) -> z*/ $f): self /*of z*/
    {
        return $this->bind(
            function ($x) use ($f, $mY): self/*of z*/ {
                return $mY->map(
                    function ($y) use ($f, $x)/*: z*/ {return $f($x, $y);}
                );
            }
        );
    }
}
