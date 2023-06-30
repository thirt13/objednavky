<?php

declare(strict_types=1);

namespace App\Components\User;

interface UserControlFactory
{
	public function create(int $id): UserControl;
}