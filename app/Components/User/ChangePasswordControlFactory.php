<?php

declare(strict_types=1);

namespace App\Components\User;

interface ChangePasswordControlFactory
{
	public function create(int $id): ChangePasswordControl;
}