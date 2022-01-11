function Maybe () {}
Maybe.nothing = () => new Nothing();
Maybe.just    = a  => new Just(a);

Maybe.ifTrue = (flag, a) => flag ? Maybe.just(a) : Maybe.nothing();
Maybe.ifSuch = (prop, a) => Maybe.ifTrue(prop(a), a);
Maybe.ifFalse = (flag, value) => Maybe.ifTrue(!flag, value);
Maybe.asTruey = value => Maybe.ifTrue(value, value);
Maybe.ifTrue_lazy  = (flag, execute) => flag ? Maybe.just(execute()) : Maybe.nothing();

Maybe.return = Maybe.just;
Maybe.fail   = Maybe.nothing;

Maybe.prototype.map = function (f)
{
	return this.maybe_exec(
		() => Maybe.nothing(), // this
		a  => Maybe.just(f(a))
	);
};

Maybe.prototype.join = function ()
{
	return this.maybe_exec(
		() => Maybe.nothing(), // this
		ma => ma
	);
};

Maybe.prototype.bind = function (mf) // Algebraic (non-optimized) def: `this.bind (mf)` = `this.map(mf).join()`
{
	return this.maybe_exec(
		() => Maybe.nothing(), // this
		mf
	);
};


Maybe.prototype.isJust    = function () {return this.maybe_val(false, _ => true );};
Maybe.prototype.isNothing = function () {return this.maybe_val(true , _ => false);};


Maybe.prototype.fromJustWith = function (errorMsg)
{
	return this.maybe_exec(
		()  => Logger.write(errorMsg),
		val => val
	);
};

Maybe.prototype.filter = function (prop)
{
	return this.bind(
		a => Maybe.ifSuch(prop, a)
	);
};

Maybe.prototype.toList = function () {return this.maybe_val([], a => [a]);};

// @TODO: Control.Monad.liftM2
Maybe.prototype.liftM2 = function (f, mb)
{
	return this.bind(
		a => mb.map(
			b => f(a, b)
		)
	);
};

Maybe.prototype.assoc = function ()
{
	return this.maybe_exec(
		() => Either.left(false),
		maybeA => maybeA.maybe_exec(
			() => Either.left(true),
			Either.right
		)
	);
};

/** Nothing: */

function Nothing () {Maybe.call(this);}
Nothing.prototype = Object.create(Maybe.prototype);
Nothing.prototype.constructor = Nothing;

Nothing.prototype.maybe_exec = function (nothing, just) {return nothing();}; // typing differs
Nothing.prototype.maybe_val  = function (nothing, just) {return nothing  ;}; // typing differs

/** Just: */

function Just(a)
{
	Maybe.call(this);
	this.a = a;
}

Just.prototype = Object.create(Maybe.prototype);
Just.prototype.constructor = Just;

Just.prototype.maybe_exec = function (nothing, just) {return just(this.a);}; // typing differs
Just.prototype.maybe_val  = function (nothing, just) {return just(this.a);}; // typing differs

/** Other aux functions */

Maybe.number = function (rep)
{
	const num = Number(rep); // @TODO `Number` is too tolerant, it accepts '' and ' ' and converts them to 0
	return Maybe.ifFalse(Number.isNaN(num), num);
};

Maybe.toInt = function (rep)
{
	const isInt = /^[\+\-]?(0|[1-9]\d*)$/.test(rep);
	return Maybe.ifTrue(isInt, Number(rep));
};

Maybe.fromObsolete = obsoleteMaybeA => maybe_exec(Maybe.nothing, Maybe.just, obsoleteMaybeA);
