<?php
declare(strict_types=1);

namespace App\Presenters;

class OrdersOldTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public OrdersOldPresenter $presenter;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
	public int $page;
	public array $items;
	public int $lastPage;
}