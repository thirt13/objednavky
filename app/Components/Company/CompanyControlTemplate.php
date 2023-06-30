<?php

declare(strict_types=1);

namespace App\Components\Company;

class CompanyTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public CompanyControl $control;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
}