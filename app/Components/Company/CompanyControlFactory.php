<?php

declare(strict_types=1);

namespace App\Components\Company;

interface CompanyControlFactory
{
	public function create(int $userID, int $id): CompanyControl;
}