<?php
declare(strict_types=1);


namespace App\Presenters\templates\School;

class SchoolTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
    public array $items;
}