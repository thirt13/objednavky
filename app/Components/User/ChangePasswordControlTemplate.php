<?php

declare(strict_types=1);

namespace App\Components\User;

class ChangePasswordTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public ChangePasswordControl $control;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
}