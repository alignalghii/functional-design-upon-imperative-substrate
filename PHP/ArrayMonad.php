<?php

namespace App\Utils\FunctionalProgramming;

/** @TODO: Use simple namespace instead of static class */
class ArrayMonad
{
	public static function flatMap(callable $declineThrough, array $words): array
	{
		return array_merge(
			...array_map($declineThrough, $words)
		);
	}
}
