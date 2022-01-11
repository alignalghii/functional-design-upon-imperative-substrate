function Either() {}

Either.left  = a => new Left (a);
Either.right = b => new Right(b);

Either.return = Either.right;
Either.fail   = Either.left;

Either.prototype.map = function (f)
{
	return this.either(
		e => Either.left(e), // this
		x => Either.right(f(x))
	);
};

Either.fromObsolete = obsoleteEitherAB => either(Either.left, Either.right, obsoleteEitherAB);

/** Left: */

function Left(a)
{
	Either.call(this); // here only for selfdoc of typing
	this.a = a;
}

Left.prototype = Object.create(Either.prototype);
Left.prototype.constructor = Left;

Left.prototype.either = function (left, right) {return left(this.a);};

/** Right: */

function Right(b)
{
	Either.call(this); // here only for selfdoc of typing
	this.b = b;
}

Right.prototype = Object.create(Either.prototype);
Right.prototype.constructor = Right;

Right.prototype.either = function (left, right) {return right(this.b);};
