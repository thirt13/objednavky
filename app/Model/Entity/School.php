<?php
declare(strict_types=1);

use Nette\Utils\DateTime;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

final class School
{

    public function __construct(
        public int $id,
        public string $name,
        public string $street,
        public int $zip,
        public string $city,
        public string $country,
        public int $ic,
        public string $dic,
        public string $phone,
        public string $account_number,
        public string $email,
        public string $www,
        public DateTime $created_at,
        public string $director,
        public int $active,
    )
    {
        
    }

    public static function create(?ActiveRow $activeRow): ?self
    {
        if ($activeRow === null)
            return null;

        return new School(...$activeRow->toArray());
    }

    public static function actual(?Selection $selection): ?self
    {
        if (count($selection) == 0)
            return null;

        $activeRow = $selection->fetch();
        return new School(...$activeRow->toArray());
    }

    
    public static function all(?Selection $selection): array
    {
        if (count($selection) == 0)
            return [];

        $all_providers = array_map('School::create', $selection->fetchAll());
        return $all_providers;
    }


}