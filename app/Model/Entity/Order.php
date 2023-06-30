<?php
declare(strict_types=1);

use Nette\Utils\DateTime;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class Order
{

    public function __construct(
        public int $id,
        public int $order_number,
        public int $school_id,
        public DateTime $created_at,
        public string $finish,
        public int $year,
        public int $providers_id,
        public string $order_item,
        public string $detail,
        public string $director,
        public string $pdf,
        public int $price,
        public int $active,
        public int $users_id,
        public string $name = "",
    )
    {
        
    }


    public static function create(?ActiveRow $activeRow): ?self
    {
        if ($activeRow === null)
            return null;

        return new Order(...$activeRow->toArray());
    }

    
    public static function all(?Selection $selection): array
    {
        if (count($selection) == 0)
            return [];

        $all_providers = array_map('Order::create', $selection->fetchAll());
        return $all_providers;
    }


}