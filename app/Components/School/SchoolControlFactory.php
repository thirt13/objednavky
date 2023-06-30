<?php

declare(strict_types=1);

namespace App\Components\School;

interface SchoolControlFactory
{
	public function create(int $id): SchoolControl;
}