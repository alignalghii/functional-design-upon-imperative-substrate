<?php

namespace App\Utils\FunctionalProgramming;

/**
 * The `Either` algebraic datatype, known from Haskell. It is a variant/sum type construct, a tagged union.
 * https://en.wikipedia.org/wiki/Tagged_union
 * https://hackage.haskell.org/package/base-4.15.0.0/docs/Data-Either.html
 * For usage details, see its unit tests in the corresponding unittest folder.
 */

abstract class Either
{
    use Monad;


    protected $value;
    public function __construct($value) {$this->value = $value;}


    public static function left ($x): self {return new Either_Left ($x);}
    public static function right($y): self {return new Either_Right($y);}

    public abstract function either(callable /*x -> a*/ $leftCase, callable /*y -> a*/ $rightCase)/*: a*/;

    public static function unit($y): self {return self::right($y);}

    public function map(callable /*y -> a*/ $mapper): /*self x, y -> */ self /*x, a*/
    {
        return $this->either(
            [self::class, 'left'],
            function ($y) use ($mapper): self {return self::right($mapper($y));}
        );
    }

    public function join(): /*x, self x y ->*/ self /*x y*/
    {
        return $this->either(
            [self::class, 'left'],
            [Combinator::class, 'id']
        );
    }

    public function eq(self /*x, y*/ $eitherXY_): bool
    {
        return $this->either(
            function ($x) use ($eitherXY_): bool {
                return $eitherXY_->either(
                    function ($x_) use ($x): bool {return $x_ == $x;},
                    function ($y_)         : bool {return false;}
                );
            },
            function ($y) use ($eitherXY_): bool {
                return $eitherXY_->either(
                    function ($x_)         : bool {return false;},
                    function ($y_) use ($y): bool {return $y_ == $y;}
                );
            }
        );
    }
}
