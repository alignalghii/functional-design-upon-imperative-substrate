<?php

namespace App\Utils\FunctionalProgramming;

use Exception; // `fromJust_unsafe` and `fromJust_unsafe_withErrorMsg`

/**
 * Haskell's `Maybe` alegebraic datatype
 * implemented in a way of analogously to Scala's case classes and case object https://nrinaudo.github.io/scala-best-practices/definitions/adt.html
 */

abstract class Maybe
{
    use Monad;

	public abstract function maybe_exec(callable /*y*/ $nothingCase, callable /*x -> y*/ $justCase)/*: y*/;

	public static function just($a) :self {return new Maybe_Just ($a);}
	public static function nothing():self {return new Maybe_Nothing();}

    function maybe_val(/*y*/ $nothingValue, callable /*x -> y*/ $justCase)/*: y*/
    {
        return $this->maybe_exec(
            function () use ($nothingValue) {return $nothingValue;},
            $justCase
        );
    }

	function map(callable $f): self
	{
		return $this->maybe_val(
			self::nothing(),
			function ($value) use ($f) {return self::just($f($value));}
		);
	}

    public static function unit($x): self /*of x*/ {return self::just($x);}

    function join(): /*self of self of x ->*/ self /*of x*/
    {
        return $this->fromMaybe(
            Maybe::nothing()
        );
    }

	public function isJust(): bool
	{
		return $this->maybe_val(
			false,
			function ($_): bool {return true;}
		);
	}

	public function isNothing(): bool
	{
		return $this->maybe_val(
			true,
			function ($_): bool {return false;}
		);
	}


	public function fromMaybe(/*a*/ $defaultVal) /*: a*/ {return $this->maybe_val($defaultVal, [Combinator::class, 'id']);}
	public function fromMaybe_exec(callable /*() -> a*/ $defaultRun) /*: a*/ {return $this->maybe_exec($defaultRun, [Combinator::class, 'id']);}

    public function fromJust_unsafe() // can throw exception
    {
        return $this->fromJust_unsafe_withErrorMsg('Partial function called: `fromJust` is the only partial function of `Maybe`');
    }

    public function fromJust_unsafe_withErrorMsg(string $errorMessage) // can throw exception
    {
        return $this->fromMaybe_exec(
            function () {throw new Exception($errorMessage);}
        );
    }

	public function mplus(Maybe/*a*/ $maybeOther): Maybe/*a*/
	{
		return $this->maybe_exec(
			function (  ) use ($maybeOther): Maybe/*a*/ {return $maybeOther   ;},
			function ($a)                  : Maybe/*a*/ {return self::just($a);}
		);
	}

    public function eq(Maybe $maybeOther): bool
    {
        return $this->maybe_exec(
            function ()         use ($maybeOther): bool {return $maybeOther->isNothing();},
            function ($thisVal) use ($maybeOther): bool
            {
                return $maybeOther->maybe_val(
                    false,
                    function ($otherVal) use ($thisVal): bool {return $otherVal == $thisVal;}
                );
            }
        );
    }

    public static function boolToMaybe(bool $flag, $value): self /*value*/ // See Hoogle search `Bool -> a -> Maybe a`
    {
        return $flag ? self::just($value) : self::nothing();
    }
}
