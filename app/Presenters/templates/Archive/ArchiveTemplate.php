<?php
declare(strict_types=1);

namespace App\Presenters;

class ArchiveTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public ArchivePresenter $presenter;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
	public int $page;
	public int $year;
	public array $items;
	public int $lastPage;
}