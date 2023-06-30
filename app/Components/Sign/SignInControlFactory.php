<?php

declare(strict_types=1);

namespace App\Components\Sign;

interface SignInControlFactory
{
	public function create(): SignInControl;
}