<?php
declare(strict_types=1);

namespace App\Presenters;

class AddCompanyTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public AddCompanyPresenter $presenter;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
	public int $page;
	public array $items;
	public int $lastPage;
}