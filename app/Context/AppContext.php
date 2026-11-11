<?php

namespace App\Context;

use App\Models\Application;
use Illuminate\Http\Request;

readonly class AppContext
{
	public function __construct(
		public Application $app,
	)
	{
	}

	public function __invoke(): Application
	{
		return $this->app;
	}
}
