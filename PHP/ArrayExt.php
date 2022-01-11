<?php

namespace App\Utils\FunctionalProgramming;

use App\Utils\FunctionalProgramming\Maybe;

/** @TODO: Use simple namespace instead of static class */
class ArrayExt
{
    public static function keyedBiMap(callable $biMapper, array $keyedArray): array
    {
        return array_map(
            $biMapper,
            array_keys($keyedArray),
            array_values($keyedArray)
        );
    }

    public static function maybeHead(array $as): Maybe /*a*/
    {
        return count($as) > 0 ? Maybe::just(array_values($as)[0]) : Maybe::nothing();
    }

    public static function excludeKeys(array $arr, $keys): array
    {
        return array_diff_key(
            $arr,
            array_flip($keys)
        );
    }

    public static function mapFromMaybe(callable/*x->y*/ $xToY , array $maybeXs, $defaultY)
    {
        return array_map(
            function ($maybeX) use ($defaultY, $xToY) {
                return $maybeX->maybe_val($defaultY, $xToY);
            },
            $maybeXs
        );
    }

    public static function mapMaybe(callable /*x -> Maybe of y*/ $xToMaybeY, array $xs): array /*of y*/
    {
        return array_reduce(
            $xs,
            function (array $ys, $x) use ($xToMaybeY): array /*of y*/ {
                return $xToMaybeY($x)->maybe_val(
                    $ys,
                    function ($y) use ($ys): array /*of y*/ {return ArrayExt::push_immutable($ys, $y);}
                );
            },
            []
        );
    }

    public static function maybeAt(array $keyToVal, $key)
    {
        return array_key_exists($key, $keyToVal)
             ? Maybe::just($keyToVal[$key])
             : Maybe::nothing();
    }

    public static function mapAssoc(array $keyToValue, array $keys): array /*of value*/
    {
        return self::mapMaybe(
            function ($key) use ($keyToValue): Maybe /*of value*/ {return ArrayExt::maybeAt($keyToValue, $key);},
            $keys
        );
    }

    public static function push_immutable(array $xs, $x): array /*of x*/ {return array_merge($xs, [$x]);}

    public static function uncons_mutable_unsafe(array $nonEmpty): array
    {
        $head = array_shift($nonEmpty); // can throw exception
        return [$head, $nonEmpty];
    }

    public static function maybeOccurringIn($x, array $xs): Maybe /*x*/ {return in_array($x, $xs) ? Maybe::just($x) : Maybe::nothing();}
}
