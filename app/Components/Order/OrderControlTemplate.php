<?php
declare(strict_types=1);

namespace App\Components\Order;
use App\Model\Entity\School;
use App\Model\Entity\Provider;
use App\Model\Entity\Order;

class OrderTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
	use \Nette\SmartObject;

	public OrderControl $control;
	public \Nette\Security\User $user;
	public string $baseUrl;
	public string $basePath;
	public array $flashes;
	public School $school;
	public Provider $provider;
	public Order $order;
}