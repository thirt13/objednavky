<?php

declare(strict_types=1);

namespace App\Components\Sign;

// use Latte\Runtime\Template;
use Nette\Bridges\ApplicationLatte\Template;

class SignInControlTemplates extends Template
{

	public string $baseUrl;
	public string $basePath;
	public array $flashes;
}