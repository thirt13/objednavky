<?php

declare(strict_types=1);

namespace App\Components\Order;

interface OrderControlFactory
{
	public function create(int $userID, int $id): OrderControl;
}