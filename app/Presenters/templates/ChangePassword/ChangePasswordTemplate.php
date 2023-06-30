<?php
declare(strict_types=1);

namespace App\Presenters;

class ChangePasswordTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public ChangePasswordPresenter $presenter;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
}