<?php
declare(strict_types=1);

use Nette\Utils\DateTime;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class User
{
    public function __construct(
        public int $id,
        public string $username,
        public string $password,
        public string $role,
        public string $name,
        public DateTime $created_at,
        public int $active,
    )
    {
        
    }

    public static function create(?ActiveRow $activeRow): ?self
    {
        if ($activeRow === null)
            return null;

        return new User(...$activeRow->toArray());
    }

    public static function all(?Selection $selection): array
    {
        if (count($selection) == 0)
            return [];

        $all_users = array_map('User::create', $selection->fetchAll());
        return $all_users;
    }

}