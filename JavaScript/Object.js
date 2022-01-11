Object.defineProperty(
	Object.prototype,
	'maybeAt',
	{
		value:
		function (i)
		{
			return Maybe.ifTrue_lazy(
				i in this,
				() => this[i]
			);
		}
	}
);
