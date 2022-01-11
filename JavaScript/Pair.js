function Pair(a, b)
{
	this.a = a;
	this.b = b;
}

Pair.prototype.uncurry = function (f) {return f(this.a, this.b);}; // @TODO: this is not an uncurry in the Haskell sense, name it as `pair`, or `unpair`.

Pair.prototype.fst = function () {return this.uncurry((a, b) => a);};
Pair.prototype.snd = function () {return this.uncurry((a, b) => b);};

Pair.prototype.conjoint = ab_cd =>
	ab_cd.uncurry(
		(ab, cd) => new Pair( // ac_bd
			new Pair(ab.fst(), cd.fst()), // a c
			new Pair(ab.snd(), cd.snd())  // b d
		)
	);
