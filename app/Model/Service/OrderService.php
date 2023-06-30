<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;

class OrderService
{


    public function __construct(
		    private Nette\Database\Explorer $database
    ) 
    {

    }


    public function getOrder(int $id): ?Order
    {
        return  Order::create($this->database->table("orders")->get($id));
    }

    public function maxNumber(): ?int 
    {
        $year = Date("Y");
        return $this->database->table("orders")->where("year ?", $year)->max("order_number");
    }

    public function allOrders(
        int $page, 
        int $offset, 
        int $lastPage,
        int $year
    ): array
    {
       
        $question = $this->database->table("orders")->select("orders.*, providers.name")->where("year ? ", $year)->order("order_number DESC, active DESC");
  
        if (count($question) > 12) {
          $question = $question->page($page, $offset, $lastPage);
        }

        return Order::all($question);

    }
    

    public function lastPageOrders(
        int $offset, 
        int $year
    ): int 
    {
        $count = count($this->database->table("orders")->where("year ? ", $year)->order("order_number DESC, active DESC"));
        return intdiv($count, $offset) + 1;
    }
   

    public function add(Nette\Utils\ArrayHash $values): int
    {
        $values->active = $values->active ? 0 : 1;

        $values->pdf =  "";
        $in = $this->database->table("orders")->insert($values);
        $this->database->table("orders")->where("id ?", $in->id)->update([
            "pdf" => sprintf("%03d", $values->order_number).Date("Y")."_objednavka".$in->id.".pdf",
        ]);
        FileSystem::createDir("objednavky\\".Date("Y"));
        return $in->id;
    
    }

    public function upd(Nette\Utils\ArrayHash $values): void
    {
        $id = $values->id;
        unset($values->id);
        $values->active = $values->active ? 0 : 1;
        $upd = $this->database->table("orders")->where("id ?", $id)->update($values);

    }
    
    public function del(int $order_id): void
    {
      $this->delFile($order_id);
      $this->database->table("orders")->where("id ?", $order_id)->delete();
    
    }

    public function delFile(int $order_id): void 
    {
      $obj = $this->getOrder($order_id);
      $path = "objednavky\\".$obj->year."\\".$obj->pdf;
      $path2 = preg_replace('/(.*)(\.\w+)/', '$1-1$2', $path);
      FileSystem::delete($path);
      FileSystem::delete($path2);
    }

}

